<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForMaintenanceMode
{
	protected $request;
	protected $app;
	public function __construct(Application $app, Request $request)
	{
	$this->app = $app;
	$this->request = $request;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  IlluminateHttpRequest  $request
	 * @param  Closure  $next
	 * @return mixed
	 */

    public function handle($request, Closure $next)

	{
		// dd($this->request->getClientIp());
	if ($this->app->isDownForMaintenance() &&
	!in_array($this->request->getClientIp(), ['127.0.0.1','::1'])) //add IP addresses you want to exclude
	{
	throw new HttpException(503);

	}
	return $next($request);

	}
}