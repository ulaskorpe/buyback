

@if($locations->count()>0)
<div class="row">
    <div class="col-md-12 ">
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Konum</th>
            <th>Sıra</th>

            <th class="text-center">İşlemler</th>
        </tr>
        </thead>
        <tbody>
    @foreach($locations as $location)

            <tr>
                <td>
                    {{$location->location()->first()->name}}
                </td>
                <td style="width: 100px">

                    <select name="order_{{$location['id']}}" id="order_{{$location['id']}}" onchange="changeLocationOrder({{$location['id']}},this.value)" class="form-control">
                        @for($i=1;$i<$count_locations[$location['location_id']]+1;$i++)
                            <option value="{{$i}}" @if($i==$location['order']) selected @endif>{{$i}}</option>
                            @endfor
                    </select>

                </td>
                <td>
                    <button class="btn btn-danger" onclick="deleteLocation({{$location['id']}})"><i class="fa fa-close"></i></button>
                </td>
            </tr>

    @endforeach
        </tbody>
    </table>

</div></div>
    @endif
