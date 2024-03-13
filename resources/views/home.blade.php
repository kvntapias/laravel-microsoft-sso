<h1>Hola </h1>

@if (Auth::user())
    <h4>Te haz logueado Como {{ Auth::user()->full_name }}</h4>
@else 
    <h4>No ha iniciado sesión</h4>
@endif
