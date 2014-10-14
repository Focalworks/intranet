@section('content')
<div class="row">
    <div class="col-md-12"><h1>Mailing archive</h1></div>
</div>
<div class="row">
    @if ( count($mail_entries) > 0)
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th>To</th>
                <th>From</th>
                <th>Subject</th>
                <th>Body</th>
                <th>Created</th>
                <th>Sent</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($mail_entries as $row)
            <tr class="{{($row->mail_status == 1) ? 'sent' : 'pending'}}">
                <td>{{$row->mail_from_name}} &lt;{{$row->mail_from_address}}&gt;</td>
                <td>{{$row->mail_to_name}} &lt;{{$row->mail_to_address}}&gt;</td>
                <td>{{$row->mail_subject}}</td>
                <td>{{MailTracker::trim_text_with_dots($row->mail_body, 50)}}</td>
                <td>{{date('d-M-Y H:m:s', $row->mail_created)}}</td>
                <td>{{date('d-M-Y H:m:s', $row->mail_sent)}}</td>
                <td>{{($row->mail_status == 1) ? 'Sent' : 'Pending'}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{$mail_entries->links()}}
    </div>
    @else
    <div class="col-md-12">No records yet.</div>
    @endif
</div>
@show