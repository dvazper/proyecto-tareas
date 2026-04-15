@extends('layouts.app')

@section('title', 'Crear cliente')

@section('content')

<h2>Crear cliente</h2>

<form method="post" action="/proyecto-tareas/proyecto/public/clients" style="max-width: 700px;">
    @csrf

    <!-- CIF -->
    <label>CIF <span style="color:red;">*</span></label>
    <input type="text" name="cif" value="{{ $datosValidados['cif'] ?? '' }}" placeholder="A12345678">
    @if(!empty($erroresValidacion['cif']))
        <p class="msg error">{{ $erroresValidacion['cif'] }}</p>
    @endif

    <!-- Nombre -->
    <label>Nombre <span style="color:red;">*</span></label>
    <input type="text" name="nombre" value="{{ $datosValidados['nombre'] ?? '' }}" placeholder="Nombre de la empresa" maxlength="255">
    @if(!empty($erroresValidacion['nombre']))
        <p class="msg error">{{ $erroresValidacion['nombre'] }}</p>
    @endif

    <!-- Código de país y teléfono -->
    <div style="display: flex; gap: 0.5rem;">
        <div style="flex: 0 0 150px;">
            <label>País <span style="color:red;">*</span></label>
            <select name="codigo_pais">
                <option value="">Selecciona país</option>
                @foreach($codigosPaises as $pais => $codigo)
                    <option value="{{ $codigo }}" @if(($datosValidados['codigo_pais'] ?? '') === $codigo) selected @endif>
                        {{ $pais }} {{ $codigo }}
                    </option>
                @endforeach
            </select>
            @if(!empty($erroresValidacion['codigo_pais']))
                <p class="msg error">{{ $erroresValidacion['codigo_pais'] }}</p>
            @endif
        </div>

        <div style="flex: 1;">
            <label>Teléfono <span style="color:red;">*</span></label>
            <input type="tel" name="telefono" value="{{ $datosValidados['telefono'] ?? '' }}" placeholder="612345678">
            @if(!empty($erroresValidacion['telefono']))
                <p class="msg error">{{ $erroresValidacion['telefono'] }}</p>
            @endif
        </div>
    </div>

    <!-- Correo -->
    <label>Correo <span style="color:red;">*</span></label>
    <input type="email" name="correo" value="{{ $datosValidados['correo'] ?? '' }}" placeholder="info@empresa.com">
    @if(!empty($erroresValidacion['correo']))
        <p class="msg error">{{ $erroresValidacion['correo'] }}</p>
    @endif

    <!-- Cuenta corriente -->
    <label>Cuenta corriente (IBAN) <span style="color:red;">*</span></label>
    <input type="text" name="cuenta_corriente" value="{{ $datosValidados['cuenta_corriente'] ?? '' }}" placeholder="ES9121000418450200051332">
    @if(!empty($erroresValidacion['cuenta_corriente']))
        <p class="msg error">{{ $erroresValidacion['cuenta_corriente'] }}</p>
    @endif

    <!-- País -->
    <label>País <span style="color:red;">*</span></label>
    <select name="pais">
        <option value="">Selecciona país</option>
        @foreach($listaPaises as $pais => $codigo)
            <option value="{{ $pais }}" @if(($datosValidados['pais'] ?? '') === $pais) selected @endif>{{ $pais }}</option>
        @endforeach
    </select>
    @if(!empty($erroresValidacion['pais']))
        <p class="msg error">{{ $erroresValidacion['pais'] }}</p>
    @endif

    <!-- Moneda -->
    <label>Moneda <span style="color:red;">*</span></label>
    <select name="moneda">
        <option value="">Selecciona moneda</option>
        @foreach($monedas as $moneda)
            <option value="{{ $moneda }}" @if(($datosValidados['moneda'] ?? '') === $moneda) selected @endif>{{ $moneda }}</option>
        @endforeach
    </select>
    @if(!empty($erroresValidacion['moneda']))
        <p class="msg error">{{ $erroresValidacion['moneda'] }}</p>
    @endif

    <!-- Importe cuota mensual -->
    <label>Importe cuota mensual <span style="color:red;">*</span></label>
    <input type="number" name="importe_cuota_mensual" value="{{ $datosValidados['importe_cuota_mensual'] ?? '' }}" step="0.01" min="0" max="999999.99" placeholder="0.00">
    @if(!empty($erroresValidacion['importe_cuota_mensual']))
        <p class="msg error">{{ $erroresValidacion['importe_cuota_mensual'] }}</p>
    @endif

    <button type="submit" class="button-link" style="margin-top: 1rem;">Guardar cliente</button>
</form>

@endsection
