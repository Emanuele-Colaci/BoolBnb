<?php

namespace App\Http\Controllers\Admin;

use App\Models\service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreserviceRequest;
use App\Http\Requests\UpdateserviceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $datas = $request->all();

        if(isset($datas['message'])){
            $message = $datas['message'];
        }else{
            $message = '';
        }

        $services = Service::all();

        return view('admin.services.index', compact('services', 'message'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreserviceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreserviceRequest $request)
    {
        $form_data = $request->all();

        $service = new Service();
        
        $service->fill($form_data);
        
        $service->save();
        
        $message = 'Creazione servizio completata';
        return redirect()->route('admin.services.index', ['message' => $message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(service $service)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateserviceRequest  $request
     * @param  \App\Models\service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateserviceRequest $request, service $service)
    {
        $form_data = $request->all();

        $service->update($form_data);

        $message = 'Aggiornamento servizio completato';
        return redirect()->route('admin.services.index', ['message' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(service $service)
    {
        $service->delete();
        $message = 'Cancellazione servizio completata';
        return redirect()->route('admin.services.index', ['message' => $message]);
    }
}
