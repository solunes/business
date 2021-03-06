@extends('master::layouts/admin')

@section('content')

  <h3>Buscar Productos</h3>
  <p id="notification-bar"></p>
  <div class="row">
    @if(config('business.product_barcode'))
      {!! Field::form_input($i, $dt, ['name'=>'barcode', 'required'=>true, 'type'=>'string'], ['label'=>'Introduzca el código de barras o utilce el lector de código de barras', 'cols'=>6]) !!}
    @endif
    {!! Field::form_input($i, $dt, ['name'=>'search-product', 'required'=>true, 'type'=>'select', 'options'=>$products], ['label'=>'Seleccione un producto ', 'cols'=>6]) !!}
  </div>
  @if($product)
    <h3>Producto: {{ $product->name }}</h3>
    <div class="row">
      <div class="col-sm-4"><strong>Categoría: </strong> {{ $product->category->name }}</div>
      <div class="col-sm-4"><strong>Nombre de Producto: </strong> {{ $product->name }}</div>
      <div class="col-sm-4"><strong>Moneda: </strong> {{ $product->currency->name }}</div>
      <div class="col-sm-12"><h3><strong>Precio con Factura: </strong> {{ $product->price.' '.$product->currency->name }}</h3></div>
      <div class="col-sm-4"><strong>Costo de Compra: </strong> {{ $product->cost.' '.$product->currency->name }}</div>
      <div class="col-sm-4"><strong>Precio sin Factura: </strong> {{ $product->no_invoice_price.' '.$product->currency->name }}</div>
      @if(config('solunes.inventory'))
      <div class="col-sm-4"><strong>Total de Stock: </strong> {{ $product->total_stock }}</div>
      @endif
    </div>
    @if(config('solunes.inventory'))
    <h3>Stock Total de Producto</h3>
    <table class="table" id="products">
      <thead>
        <tr class="title">
          <td>Lugar</td>
          <td>Cantidad Inicial</td>
          <td>Cantidad Actual</td>
        </tr>
      </thead>
      <tbody>
        @foreach($product->product_stocks as $stock)
          <tr>
            <td>{{ $stock->place->name }}</td>
            <td>{{ $stock->initial_quantity }}</td>
            <td>{{ $stock->quantity }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  @endif

@endsection
@section('script')
  @include('master::scripts.select-js')
  @include('store::scripts.search-product-js')
@endsection