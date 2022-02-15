@extends('admin.main_layout')
@section('css_')

@endsection
@section('main')

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header ">
                        <div class="row">
                            <div class="col-2">
                                <select name="count_cats" id="count_cats" class="form-control mb-2">
                                    @for($i=500;$i<2001;$i+=500)
                                        <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                </select>
                                <button type="button" class="btn btn-primary" onclick="showCats()">
                                    Kategoriler Listele
                                </button>

                            </div>
                            <div class="col-2">
                                <input type="text"class="form-control mb-2" id="cat_id" name="cat_id">
                                <button type="button" class="btn btn-primary" onclick="showCatDetail()">
                                    Kategoriler Detay
                                </button>
                            </div>
                            <div class="col-3">

                                <button type="button" class="btn btn-primary" onclick="showCats()">
                                    Kategoriler Listele
                                </button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-primary" onclick="showCats()">
                                    Kategoriler Listele
                                </button>
                            </div>

                        </div>

                    </div>
                    <div class="card-body">
                        <div id="result">


                        </div>
                    </div>

                </div>
                <!-- /basic datatable -->
            </div>
            <!-- Inline form modal -->
        </div>
    </div>
@endsection
@section('scripts')




    <script>
        $(document).ready(function () {

        });

        function showCats(){
            $.get( "{{route('market.hepsi-burada-cats')}}", function( data ) {
          //  $.get( "https://mpop-sit.hepsiburada.com/product/api/categories/get-all-categories?leaf=true&status=ACTIVE&available=true&page=0&size=1000&version=1", function( data ) {
                $( "#result" ).html( data );

            });
        }
        function showCatDetail(){
            if($('#cat_id').val()==''){
                swal('Kategori ID giriniz');
            }else{
            $.get("{{url('admin/market-place/hepsi-burada-cat-detail')}}/"+$('#cat_id').val(), function( data ) {

                $( "#result" ).html( data );

            });
            }
        }
    </script>

@endsection
