<div class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header"><b>{{$contact['subject']}}</b></div>
                <div class="card-body">

                    <div class="text-left">
                    {{$contact['message']}}
                    </div>

                </div>
                <div class="card-footer">{{$contact['name']}} {{$contact['surname']}} / {{$contact['email']}} , {{$contact['phone_number']}}</div>
            </div>
        </div>
    </div>
</div>