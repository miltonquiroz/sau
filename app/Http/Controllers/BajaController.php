<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Solicitud as ModelsSolicitud;
use Barryvdh\DomPDF\Facade\Pdf;

class BajaController extends Controller
{
    public function index(){
        return view('users.baja.baja');
    }

    public function downloadPdf($solicitud){
        $path = storage_path('pdf/');
        $pdf_name = $solicitud->fileID.'_sau.pdf';
        $pdf = Pdf::loadView('users.baja.pdf.sbu', array('solicitud'=>$solicitud));
        $pdf->save($path.'/'.$pdf_name);
        $pdf->setPaper('a4');
        return $pdf->stream($pdf_name);
    }

    public function crear (Request $request){
        $solicitud = new  ModelsSolicitud();
        $solicitud->autorizador = $request->autorizador;
        switch($solicitud->autorizador){
            case "Ing. Mario César Herrera González": 
                $solicitud->puesto = "Director Coordinador de Innovación y Desarrollo Tecnológico";
                break;
            case "Ing. José Antonio Rulfo Zaragoza": 
                $solicitud->puesto = "Director de Desarrollo Tecnológico";
                break;
            case "Mtra. Edna Patricia Santiago Vargas": 
                $solicitud->puesto = "Subdirectora de Sistemas Administrativos";
                break;
            case "Ing. David de León Muñoz": 
                $solicitud->puesto = "Subdirector de Innovación Tecnológica";
                break;
            case "Ing. Iracema Mirón Ramírez": 
                $solicitud->puesto = "Subdirectora de Adminsitración de Portales";
                break;
        }
        //Datos almacenados
        $solicitud->nombre = auth()->user()->name;
        $solicitud->apellido_paterno = auth()->user()->apellido_paterno;
        $solicitud->apellido_materno = auth()->user()->apellido_materno;
        $solicitud->emailSend = auth()->user()->email;
        $solicitud->userID = auth()->user()->id;

        $solicitud->direccion = $request->direccion;
        $solicitud->contrato = $request->contrato;
        switch($solicitud->contrato){
            case "MVC-4589":
                $solicitud->empresa = "Patito S.A. de C.V";
                break;
            case "BBC-3789":
                $solicitud->empresa = "Electronica Sacachispas S.A de C.V";
                break;
        }
        $solicitud->funcion = $request->funcion;
        $solicitud->direccion = $request->direccion;
        if(auth()->user()->vpn != ""){
            $solicitud->vpn = $request->vpn; 
        }
        else{
            $solicitud->vpn = "no" ; 
        }
        $solicitud->ip_fija = $request->ip_fija;
        
        if(auth()->user()->internet != ""){
            $solicitud->internet = $request->internet;
        }
        else{
            $solicitud->internet = "no";
        }
        if(auth()->user()->gitlab != ""){
            $solicitud->gitlab = $request->gitlab;
        }
        else{
            $solicitud->gitlab = "no";
        }
        if(auth()->user()->jira != ""){
            $solicitud->jira = $request->jira;
        }
        else{
            $solicitud->jira = "no";
        }
        if(auth()->user()->glpi != ""){
            $solicitud->glpi = $request->glpi;
        }
        else{
            $solicitud->glpi = "no";
        }

        

        $solicitud->tipo_equipo = $request->tipo_equipo;
        $solicitud->marca = $request->marca;
        $solicitud->modelo = $request->modelo;
        $solicitud->serie = $request->serie;
        $solicitud->mac = $request->mac;
        if(auth()->user()->ipFija == ""){
            $solicitud->ip_antigua = "N/A";
        }
        else{
            $solicitud->ip_antigua = $request->ip_antigua;
        }
        $solicitud->equipo_propio = $request->equipo_propio;
        $solicitud->equipo_sict = $request->equipo_sict;
        $solicitud->startTime = time();
        $solicitud->fileID = auth()->user()->id.$solicitud->startTime;

        $solicitud->save();

        $this->downloadPdf($solicitud);

        //Update query
        User::where('id', auth()->user()->id)->update([
           'solicitudes' => auth()->user()->solicitudes+1
        ]);
        
        return redirect()->route('post.index');

    }
}
