<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\EloquentUserRepository;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $email = htmlspecialchars(trim($request->input('email')));
        $repo = new EloquentUserRepository;
        $users = $repo->search(['email' => $email]);

        if (count($users) === 1)
        {
            if (!$users[0]->is_active)
            {
                 return redirect()->route('login')->with('error', 'Пользователь заблокирован');
            }
        }

        return $next($request);
    }
}
