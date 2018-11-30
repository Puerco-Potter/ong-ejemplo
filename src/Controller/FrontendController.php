<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

// Entidades
use App\Entity\Noticia;
use App\Entity\Programa;
use App\Entity\Tematica;
use App\Entity\SubTematica;
use App\Entity\Banner;
use App\Entity\Ong;
use App\Entity\User;
use App\Entity\Contenido;
use App\Entity\Agenda;
use App\Entity\Localidad;

// formulario contacto
use App\Entity\Contacto;
use App\Form\ContactoType;

// Formulario
use App\Form\OrganizacionType;

//Estadisticas
use Mukadi\Chart\Utils\RandomColorFactory;
use Mukadi\Chart\Chart;
use Mukadi\Chart\Builder;

//Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FrontendController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {   
        $banners=$this->getDoctrine()
            ->getRepository(Banner::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));

        $tematicas=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));
        //ordeno aleatoriamente las tematicas asi siempre que recargo la pagina las muestro ordenadas aletariamentes, y no tienen prioridad una sobre otra
        shuffle($tematicas);
      
        $ultimas_noticias=$this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findBy(array('publicado'=>1),array('fechaCreacion'=>'DESC'),8);

        $ultimos_programas=$this->getDoctrine()
            ->getRepository(Programa::class)
            ->findBy(array('publicado'=>1),array('fechaCreacion'=>'DESC'),6);

        return $this->render('frontend/index.html.twig',array(
            'tematicas'=>$tematicas,
            'ultimas_noticias'=>$ultimas_noticias,
            'ultimos_programas'=>$ultimos_programas,
            'banners'=>$banners
        ));
    }

    /**
     * @Route("/programas", name="programas")
     */
    public function programas()
    {   
        $tematicas=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));
        //para obtener algunas noticias
        $ultimas_noticias=$this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findBy(array('publicado'=>1),array('fechaCreacion'=>'DESC'),4);

        //para obtener el paginado de los programas
        $queryBuilder =$this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Programa', 'p')
            ->where('p.publicado=1')
            ->orderBy('p.fechaCreacion','DESC');
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $paginado_programas = new Pagerfanta($adapter);
        // el bundle ocupa como variable request el page, indicando el numero de pagina actual
        if (isset($_REQUEST['page'])) {
            $paginado_programas =$paginado_programas->setCurrentPage($_REQUEST['page']);
        };
        return $this->render('frontend/programas.html.twig', [
            'paginado_programas' => $paginado_programas,
            'ultimas_noticias'=>$ultimas_noticias,
            'tematicas'=>$tematicas
        ]);

    }

    /**
     * @Route("/genero", name="genero")
     */
    public function genero()
    {
        return $this->render('frontend/genero.html.twig');
    }

    /**
     * @Route("/institucional", name="institucional")
     */
    public function institucional()
    {
        $contenidoEntity = $this->getDoctrine()
            ->getRepository(Contenido::class)
            ->findOneByCodigo('institucional');
        if ($contenidoEntity) {
            $contenido = $contenidoEntity->getContenido();
        } else {
            $contenido = 'institucional: Contenido disponible en breve.';
        }
        return $this->render('frontend/institucional.html.twig', [
            'contenido' => $contenido,
            'contenidoEntidad' => $contenidoEntity
        ]);
    }

    /**
     * @Route("/tematicas/", name="tematicas")
     */
    public function tematicas()
    {   
        $tematicas=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));        

        return $this->render('frontend/tematicas.html.twig',array(
            'tematicas'=>$tematicas
        ));
    }

    /**
     * @Route("/ver_tematica/{id}", name="ver_tematica")
     */
    public function verTematica($id)
    {   
        // obtengo la noticia
        $tematica=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findOneBy(array('id'=>$id));         

        $noticias_relacionadas =$this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findUltNoticiasByTematicas($id);

        $programas_relacionados=$this->getDoctrine()
            ->getRepository(Programa::class)
            ->findUltProgramasByTematicas($id);
         
        return $this->render('frontend/ver_tematica.html.twig', array(
            'tematica'=>$tematica,
            'programas_relacionados'=>$programas_relacionados,
            'noticias_relacionadas'=>$noticias_relacionadas,
        ));
    }

    /**
     * @Route("/estadisticas", name="estadisticas")
     */
    public function estadisticas()
    {   
        $builder = $this->get('mukadi_chart_js.dql');

        //Por matricula
        $builder->query("SELECT 
            CASE WHEN o.matricula IS NULL THEN 'No Matriculada' ELSE 'Matriculada'END as totals, 
            COUNT(o.id) as cuenta
            FROM App\Entity\Ong o
            WHERE o.publicado=1
            GROUP BY totals");
        $builder
            ->addDataset('cuenta','Totals',[
                "backgroundColor" => ["gold", "silver"],
            ])
            ->labels('totals');
        $chartM = $builder->buildChart('Matricula',Chart::PIE);
        //quitamos el click para ocultar, porque si
        $chartM -> pushOptions([
            'legend' => [
                'onClick' => null 
            ]
        ]); 
        

        //Por Tematica
        //Crear la query con createQueryBuilder
        $em = $this->getDoctrine()->getManager();
        $myQuery =  $em ->getRepository("App\Entity\Tematica")
        ->createQueryBuilder('t')
        ->select('COUNT(o.id) AS cuenta','t.nombre')
        ->innerJoin('t.ongs', 'o')
        ->where('o.publicado=1')
        ->groupby('t.nombre');
        //pasar la query a DQL
        $QueryEnDQL = $myQuery->getDQL();
        
        //pasarle al builder de Mukadi la Query en DQL
        $builder->query($QueryEnDQL);
        $builder
            ->addDataset('cuenta','Numero de Ongs', [
                "backgroundColor" => [
                    "darkred", "lightblue", "goldenrod", "green", "orange", "violet", "pink", "blueViolet", "brown","burlywood","cadetblue","chartreuse","chocolate","crimson","cyan","darkgoldenrod","darkorange","deeppink","grey","maroon"
                ],
            ])
            ->labels('nombre');
        $chartT = $builder->buildChart('Tematica',Chart::BAR);
        //quitamos el click para ocultar, porque si
        $chartT -> pushOptions([
            'legend' => [
                'onClick' => null 
            ]
        ]);

        //Por Localidad
        $builder->query("SELECT  
            COUNT(o.id) as cuenta, o.localidad, l.Nombre
            FROM App\Entity\Ong o
            JOIN App\Entity\Localidad l
            WITH o.localidad = l.ValorAsignado
            WHERE o.publicado=1
            GROUP BY o.localidad");
        $builder
            ->addDataset('cuenta','Numero de ONGs', [
                "backgroundColor" => array_fill(0, 200, 'darkblue'),
            ])
            ->labels('Nombre');
        $chartL = $builder->buildChart('Localidad', Chart::BAR);
        //quitamos el click para ocultar, porque si
        $chartL -> pushOptions([
            'legend' => [
                'onClick' => null 
            ]
        ]);

        //Por forma juridica
        $builder->query("SELECT 
            CASE 
            WHEN o.formaJuridica = 0 THEN 'ASOCIACIÓN CIVIL' 
            WHEN o.formaJuridica = 1 THEN 'COMUNIDAD INDIGENA'
            WHEN o.formaJuridica = 2 THEN 'COOPERATIVA' 
            WHEN o.formaJuridica = 3 THEN 'FUNDACIÓN' 
            WHEN o.formaJuridica = 4 THEN 'GRUPO COMUNITARIO' 
            WHEN o.formaJuridica = 5 THEN 'GRUPO DE PERSONAS' 
            WHEN o.formaJuridica = 6 THEN 'MUTUAL' 
            WHEN o.formaJuridica = 7 THEN 'NO ESPECIFICA'  
            WHEN o.formaJuridica = 8 THEN 'SOCIEDAD DE FOMENTO' 
            ELSE 'SIN TIPO' END 
            AS forma, COUNT(o.id) as cuenta
            FROM App\Entity\Ong o
            WHERE o.publicado=1
            GROUP BY o.formaJuridica");
        $builder
            ->addDataset('cuenta','Numero de ONGs', [
                "backgroundColor" => array_fill(0, 200, 'darkred'),
            ])
            ->labels('forma');

        $chartJ = $builder->buildChart('Tipo',Chart::BAR);
        //quitamos el click para ocultar, porque si
        $chartJ -> pushOptions([
            'legend' => [
                'onClick' => null 
            ]
        ]);

        return $this->render('frontend/estadisticas.html.twig', [
            "chartM" => $chartM,
            "chartL" => $chartL,
            "chartJ" => $chartJ,
            "chartT" => $chartT
        ]);
    }

    /**
     * @Route("/excel/{tipo}", name="excel")
     */
    public function excel(string $tipo)
    {
        $spreadsheet = new Spreadsheet();

        //construimos el excel
        if ($tipo == "ongs") {

            /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("1:1")->getFont()->setBold( true );
            $sheet->setTitle("ONGs");
            $sheet->setCellValue('A1', 'Nombre de ONG');
            $sheet->setCellValue('B1', 'Localidad');
            $sheet->setCellValue('C1', 'Direccion');
            $sheet->setCellValue('D1', 'E-Mail');
            $sheet->setCellValue('E1', 'Facebook');
            $sheet->setCellValue('F1', 'Instagram');
            $sheet->setCellValue('G1', 'Twitter');
            $sheet->setCellValue('H1', 'Tematicas');

            $ongs=$this->getDoctrine()
            ->getRepository(ONG::class)
            ->findAll();

            $fila=2;
            foreach ($ongs as $ong) {
                $sheet->setCellValue('A'. $fila, $ong ->getNombreOrganizacion());
                $sheet->setCellValue('B'. $fila, $ong ->getLocalidad());
                $sheet->setCellValue('C'. $fila, $ong ->getDireccion());
                $sheet->setCellValue('D'. $fila, $ong ->getCorreo());
                $sheet->setCellValue('E'. $fila, $ong ->getFacebook());
                $sheet->setCellValue('F'. $fila, $ong ->getInstagram());
                $sheet->setCellValue('G'. $fila, $ong ->getTwitter());
                $tematicas = $ong ->getTematicas();
                $stringTematicas = "";
                foreach($tematicas as $tematica){
                    $stringTematicas = $stringTematicas . $tematica ->getNombre() . " ";
                }
                $sheet->setCellValue('H'. $fila, $stringTematicas);
                $fila+=1;
            }

            //nombre del archivo
            $fileName = 'excelONGs.xlsx';

        } elseif ($tipo == "tematicas") {

            /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("1:1")->getFont()->setBold( true );
            $sheet->setTitle("Tematicas");
            $sheet->setCellValue('A1', 'Nombre de Tematica');
            $sheet->setCellValue('B1', 'Resumen');
            $sheet->setCellValue('C1', 'Desarrollo');
            
            $Tematicas=$this->getDoctrine()
                ->getRepository(Tematica::class)
                ->findAll();

            $fila=2;
            foreach ($Tematicas as $Tematica) {
                $sheet->setCellValue('A'. $fila, $Tematica ->getNombre());
                $sheet->setCellValue('B'. $fila, $Tematica ->getResumen());
                $sheet->setCellValue('C'. $fila, $Tematica ->getDesarrollo());
                $fila+=1;
            }

            //nombre del archivo
            $fileName = 'excelTematicas.xlsx';
        } 

        // Crear Excel 2007 (XLSX)
        $writer = new Xlsx($spreadsheet);
        
        // Creo el archivo
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        // Lo guardo en el sistema
        $writer->save($temp_file);
        
        // Descarga
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/noticias", name="noticias")
     */
    public function noticias()
    {
        $tematicas=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));

        //para obtener algunos programas
        $ultimos_programas=$this->getDoctrine()
            ->getRepository(Programa::class)
            ->findBy(array('publicado'=>1),array('fechaCreacion'=>'DESC'),4);

        //para obtener el paginado de las noticias
        $queryBuilder =$this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n')
            ->from('App\Entity\Noticia', 'n')
            ->where('n.publicado=1')
            ->orderBy('n.fechaCreacion','DESC');
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $paginado_noticias = new Pagerfanta($adapter);
        // el bundle ocupa como variable request el page, indicando el numero de pagina actual
        if (isset($_REQUEST['page'])) {
            $paginado_noticias =$paginado_noticias->setCurrentPage($_REQUEST['page']);
        } ;
        
        return $this->render('frontend/noticias.html.twig', [
            'paginado_noticias' => $paginado_noticias,
            'ultimos_programas'=>$ultimos_programas,
            'tematicas'=>$tematicas
        ]);
    }

    /**
     * @Route("/programas_tematica/{tematica}", name="programas_tematica")
     */
    public function programas_tematica($tematica)
    {   
        $tematicas=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));
        
        $ultimas_noticias =$this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findUltNoticiasByTematicas($tematica);

        //para obtener el paginado de los programas
        $queryBuilder =$this->getDoctrine()->getRepository(Programa::class)
            ->findProgramasByTematica($tematica);
        
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $paginado_programas = new Pagerfanta($adapter);
        // el bundle ocupa como variable request el page, indicando el numero de pagina actual
        if (isset($_REQUEST['page'])) {
            $paginado_programas =$paginado_programas->setCurrentPage($_REQUEST['page']);
        };
        return $this->render('frontend/programas.html.twig', [
            'paginado_programas' => $paginado_programas,
            'ultimas_noticias'=>$ultimas_noticias,
            'tematicas'=>$tematicas,
            'tematica_seleccionada'=>$tematica
        ]);

    }

    /**
     * @Route("/noticias_tematica/{tematica}", name="noticias_tematica")
     */
    public function noticias_tematica($tematica)
    {
        $tematicas=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));

        //para obtener algunos programas
        $ultimos_programas =$this->getDoctrine()
            ->getRepository(Programa::class)
            ->findUltProgramasByTematicas($tematica);

        //para obtener el paginado de las noticias
        $queryBuilder =$this->getDoctrine()->getRepository(Noticia::class)
            ->findNoticiaByTematica($tematica);
        
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $paginado_noticias = new Pagerfanta($adapter);
        // el bundle ocupa como variable request el page, indicando el numero de pagina actual
        if (isset($_REQUEST['page'])) {
            $paginado_noticias =$paginado_noticias->setCurrentPage($_REQUEST['page']);
        };
        
        return $this->render('frontend/noticias.html.twig', [
            'paginado_noticias' => $paginado_noticias,
            'ultimos_programas'=>$ultimos_programas,
            'tematicas'=>$tematicas,
            'tematica_seleccionada'=>$tematica
        ]);
    }

    /**
     * @Route("/detalle_noticia/{id}", name="detalle_noticia")
     */
    public function detalle_noticia($id)
    {   
        // obtengo la noticia
        $noticia=$this->getDoctrine()
            ->getRepository(Noticia::class)
            ->findOneBy(array('id'=>$id));
        //obtengo las noticias de la ultima subcategoria
        $ultimas_noticias=null;
        $ultimas_programas=null;
        if($noticia){
            //busco las tematicas  y guardo los id para la consulta en las noticias y programas relacionados
            $tematicas=$noticia->getTematicas();
            if($tematicas){
                $tematicas_id="";
                foreach ($tematicas as $key=> $tematicas_encontradas) {
                    if($key==0){
                        $tematicas_id.="".$tematicas_encontradas->getId();
                    }else{
                        $tematicas_id.=" or t.id =".$tematicas_encontradas->getId();
                    }
                }
                $ultimas_noticias =$this->getDoctrine()
                    ->getRepository(Noticia::class)
                    ->findUltNoticiasByTematicas($tematicas_id);

                $ultimos_programas=$this->getDoctrine()
                    ->getRepository(Programa::class)
                    ->findUltProgramasByTematicas($tematicas_id);
                
            }else{
                $ultimas_noticias=$this->getDoctrine()
                    ->getRepository(Noticia::class)
                    ->findBy(array('publicado'=>1),array('fechaCreacion'=>'DESC'),3);

                $ultimos_programas=$this->getDoctrine()
                    ->getRepository(Programa::class)
                    ->findBy(array('publicado'=>1),array('fechaCreacion'=>'DESC'),3);
            }   
        }
        return $this->render('frontend/detalles_noticias.html.twig',array(
            'noticia'=>$noticia,
            'ultimas_noticias'=>$ultimas_noticias,
            'ultimos_programas'=>$ultimos_programas
        ));
    }

    /**
     * @Route("/agenda/{tematica}", name="agenda")
     */
    public function agenda($tematica=null)
    {   
        $tematicas=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));

        $queryBuilder =$this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('o')
            ->from('App\Entity\Agenda', 'o')
            ->where('o.publicado = 1')
            ->andwhere('o.fecha >= CURRENT_DATE()')
            ->orderBy('o.fecha','ASC');

        $tematica_seleccionada=null;

        if ($tematica && ($tematica!=0)) {
            $queryBuilder ->join('o.tematicas', 't',  'WITH', 't.id = :tematica')
                ->setParameter('tematica',$tematica);
            $tematica_seleccionada=$this->getDoctrine()
                ->getRepository(Tematica::class)
                ->findOneBy(array('id'=>$tematica));
        };

        $adapter = new DoctrineORMAdapter($queryBuilder);
        $paginado_agenda = new Pagerfanta($adapter);
        // el bundle ocupa como variable request el page, indicando el numero de pagina actual
        if (isset($_REQUEST['page'])) {
            $paginado_agenda =$paginado_agenda->setCurrentPage($_REQUEST['page']);
        };
        
        return $this->render('frontend/agenda.html.twig', array(
            'paginado_agenda'=>$paginado_agenda,
            'tematicas'=>$tematicas,
            'tematica_seleccionada'=>$tematica_seleccionada,
        ));
    }

    /**
     * @Route("/inscripcion-finalizada", name="inscripcion_finalizada")
     */
    public function inscripcionFinalizada(Request $request)
    {
        return $this->render('frontend/inscripcion_finalizada.html.twig');
    }

    /**
     * @Route("/listado_ong/{tematica}/{localidad_seleccionada}", name="listado_ong")
     */
    public function listadoong($tematica=null,$localidad_seleccionada=null )
    {   //para obtener las localidades
        $ong=new Ong();
        $lista_localidades=$ong->getLocalidades();

        $tematicas=$this->getDoctrine()
            ->getRepository(Tematica::class)
            ->findBy(array('publicado'=>1),array('orden'=>'ASC'));

        $queryBuilder =$this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('o')
            ->from('App\Entity\Ong', 'o')
            ->where('o.esJurisdiccion=0')
            ->andwhere('o.publicado=1');

        $tematica_seleccionada=null;

        if ($tematica && ($tematica!=0)) {
            $queryBuilder ->join('o.tematicas', 't',  'WITH', 't.id = :tematica')
                ->setParameter('tematica',$tematica);
            $tematica_seleccionada=$this->getDoctrine()
                ->getRepository(Tematica::class)
                ->findOneBy(array('id'=>$tematica));
        }
        if ($localidad_seleccionada && ($localidad_seleccionada!=0)) {
            $queryBuilder ->where('o.localidad= :localidad')
                ->setParameter('localidad',$localidad_seleccionada);
        }
        
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $paginado_ong = new Pagerfanta($adapter);
        // el bundle ocupa como variable request el page, indicando el numero de pagina actual
        if (isset($_REQUEST['page'])) {
            $paginado_ong =$paginado_noticias->setCurrentPage($_REQUEST['page']);
        };
        
        return $this->render('frontend/listado_ong.html.twig', array(
            'paginado_ong'=>$paginado_ong,
            'tematicas'=>$tematicas,
            'tematica_seleccionada'=>$tematica_seleccionada,
            'localidad_seleccionada'=>$localidad_seleccionada ,
            'lista_localidades'=>$lista_localidades
        ));
    }

    /**
     * @Route("/detalles_programas/{id}", name="detalles_programas")
     */
    public function detalles_programas($id)
    {   
        // obtengo la noticia
        $programa=$this->getDoctrine()
            ->getRepository(Programa::class)
            ->findOneBy(array('id'=>$id));
        //obtengo las noticias de la ultima subcategoria
        $ultimas_noticias=null;
        $ultimas_programas=null;
        if($programa){
            //busco las tematicas  y guardo los id para la consulta en las noticias y programas relacionados
            $tematicas=$programa->getTematicas();
            if($tematicas){
                $tematicas_id="";
                foreach ($tematicas as $key=> $tematicas_encontradas) {
                    if($key==0){
                        $tematicas_id.="".$tematicas_encontradas->getId();
                    }else{
                        $tematicas_id.=" or t.id =".$tematicas_encontradas->getId();
                    }
                }
                $ultimas_noticias =$this->getDoctrine()
                    ->getRepository(Noticia::class)
                    ->findUltNoticiasByTematicas($tematicas_id);

                $ultimos_programas=$this->getDoctrine()
                    ->getRepository(Programa::class)
                    ->findUltProgramasByTematicas($tematicas_id);
            }else{
                $ultimas_noticias=$this->getDoctrine()
                    ->getRepository(Noticia::class)
                    ->findBy(array('publicado'=>1),array('fechaCreacion'=>'DESC'),3);

                $ultimos_programas=$this->getDoctrine()
                    ->getRepository(Programa::class)
                    ->findBy(array('publicado'=>1),array('fechaCreacion'=>'DESC'),3);
            }   
        }
        return $this->render('frontend/detalles_programas.html.twig', array(
            'programa'=>$programa,
            'ultimas_noticias'=>$ultimas_noticias,
            'ultimos_programas'=>$ultimos_programas
        ));
    }

    /**
     * @Route("/registro", name="registro")
     */
    public function registro(Request $request)
    {
        $contacto = new Contacto();

        $form = $this->createForm(ContactoType::class, $contacto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contacto->setInscripcion(true);//Inscripcion true
            $em = $this->getDoctrine()->getManager();
            $em->persist($contacto);
            $em->flush();

            $this->enviarCorreo('Registro', $contacto);

            $parameters = $contacto->getCuil().
                "?nombres=".$contacto->getNombre().
                "&apellidos=".$contacto->getApellido().
                "&email=".$contacto->getEmail().
                "&telefono=".$contacto->getTelefono()
            ;

            return $this->redirect('http://stage.ventanillaunica.chaco.gov.ar/registracion/preregistro/'.$parameters);
        } else {
            return $this->render('frontend/registro.html.twig', array(
                'form' => $form->createView()
            ));
        }
    }

    /**
     * @Route("/contacto", name="contacto")
     */
    public function contacto(Request $request)
    {
        $contacto = new Contacto();

        //conseguir contenido
        $soporte_tel = $this->getDoctrine()
            ->getRepository(Contenido::class)
            ->findOneByCodigo('soporte_tel');
        $soporte_mail = $this->getDoctrine()
            ->getRepository(Contenido::class)
            ->findOneByCodigo('soporte_tel');
        $soporte_cel = $this->getDoctrine()
            ->getRepository(Contenido::class)
            ->findOneByCodigo('soporte_cel');
        $domicilio = $this->getDoctrine()
            ->getRepository(Contenido::class)
            ->findOneByCodigo('domicilio');
        $oficina_administrador = $this->getDoctrine()
            ->getRepository(Contenido::class)
            ->findOneByCodigo('oficina_administrador');

        $form = $this->createForm(ContactoType::class, $contacto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contacto);
            $em->flush();

            $this->enviarCorreo('Contacto', $contacto);

            return $this->render('frontend/contacto_exitoso.html.twig');
        } else {
            return $this->render('frontend/contacto.html.twig', array(
                'form' => $form->createView(),
                'soporte_tel' => $soporte_tel,
                'soporte_mail' => $soporte_mail,
                'soporte_cel' => $soporte_cel,
                'domicilio' => $domicilio,
                'oficina_administrador' => $oficina_administrador
            ));
        }
    }

    private function enviarCorreo($title, $contacto) {
        $message = (new \Swift_Message($title))
            ->setSubject($title.' ONG')
            ->setFrom($contacto->getEmail())
            ->setTo('ecomgrupo11@gmail.com')
            ->setBody("Nombre y Apellido: ".$contacto->getNombre().', '.$contacto->getApellido().
                "\n\n Organizacion: ".$contacto->getOrganizacion().
                "\n\n Teléfono: ".$contacto->getTelefono().
                "\n\n Email: \n\t\t   ".$contacto->getEmail().
                "\n\n Mensaje: \n\t\t   ".$contacto->getObservaciones()
            );
        $this->get('mailer')->send($message);
    }

    /**
     * @Route("/detalle_ong/{id}", name="detalle_ong")
     */
    public function detalle_ong($id)
    {   
        // obtengo la ong
        $ong=$this->getDoctrine()
            ->getRepository(Ong::class)
            ->findOneBy(array('id'=>$id));

        //obtengo las noticias de la ultima subcategoria
        $noticias=null;
        $ultimas_programas=null;
        if($ong){
            //busco las tematicas  y guardo los id para la consulta en las noticias y programas relacionados
            $tematicas=$ong->getTematicas();
            if($tematicas){
                $tematicas_id="";
                foreach ($tematicas as $key=> $tematicas_encontradas) {
                    if($key==0){
                        $tematicas_id.="".$tematicas_encontradas->getId();
                    }else{
                        $tematicas_id.=" or t.id =".$tematicas_encontradas->getId();
                    }
                }
            } 
        }
        $noticias =$this->getDoctrine()
                    ->getRepository(Noticia::class)
                    ->findBy(array('ong'=>$id, 'publicado'=>1), array('fechaCreacion'=>'DESC'), 3);

        $agendas=$this->getDoctrine()
        ->getRepository(Agenda::class)
        ->findBy(array('ong'=>$id),array('fecha'=>'DESC'));

        return $this->render('frontend/detalles_ongs.html.twig',array(
            'ong'=>$ong,
            'noticias'=>$noticias,
            'agendas'=>$agendas
        ));
    }
}
