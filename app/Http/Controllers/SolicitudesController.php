<?php

namespace App\Http\Controllers;

use auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudMailable;
use Svg\Tag\Path;

class SolicitudesController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index(){
        return view('auth.solicitud');
    }

    public function sendMail(){
        $correo = new SolicitudMailable;
        Mail::to('rarturo899@gmail.com')->send($correo->attach(storage_path('pdf/1664235532_sau.pdf')));
        return "Menasaje enviado";
    }

    public function downloadPdf(){
        $path = storage_path('pdf/');
        $pdf_name = time().'_sau.pdf';
        $pdf = Pdf::loadView('solicitud.sau');
        $pdf->save($path.'/'.$pdf_name);
        $pdf->setPaper('a4');
        return $pdf->download($pdf_name);
    }


    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|min:1|max:20',
            'apellido_paterno' => 'required|min:5|max:20',
            'apellido_materno' => 'required|min:5|max:20',
            'email' => 'required|unique:users|email|max:30',
            'autorizadores' => 'required',
            'puestos' => 'required',
            'empresas' => 'required',
            'ubicacion' => 'required',
            'contrato' => 'required',
            'funciones' => 'required|max:200',
            'equipo' => 'required',
            'marca' => 'required',
            'modelo' => 'required',
            'serie' => 'required',
            'mac' => 'required',
            'ip_antigua' => 'required',
            'equipo_propio' => 'required',
            'equipo_sict' => 'required'
        ]);

        USER::create([
            'ubicacion' => $request->ubicacion,
            'empresa' => $request->empresa,
            'contrato' => $request->contrato,
            'name' => $request->name,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'email' => $request->email,
            'password' => Hash::make ($request->password)
        ]);

        auth()->attempt($request->only('email','password'));

        return redirect()->route('posts.index');
    }


}


