
<div class="row"><div class="col-12">



<div class="x_panel">
    <div class="x_title">
        <h2>{{$model->brand()->first()->BrandName}}  {{$model['Modelname']}}  ({{$model->category()->first()->category_name}})</h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        @php
            echo $txt;
        @endphp

    </div>
</div>

    </div>


</div>
<div class="row">
    <div class="col-12 text-center">    <button class="btn btn-primary"   onclick="" >GÖNDER</button></div>
</div>
