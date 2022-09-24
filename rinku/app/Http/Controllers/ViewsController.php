<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Servicios;
// use Illuminate\Http\Request;
use Request;

class ViewsController extends Controller
{
	/* Administrador */

	/**
	 * Show the application login screen to the user.
	 *
	 * @return Response
	 */
	public function login()
	{
		return view('login');
	}

	/**
	 * Show the application admin screen to the user.
	 *
	 * @return Response
	 */
	public function admin()
	{
		return view('admin');
	}
}
