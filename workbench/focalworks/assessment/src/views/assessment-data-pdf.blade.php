<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Document</title>
<style>
    body, h1, h2, h3, h4, h5 {
        font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
    }
</style>
</head>
<body class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>User details</h1>
                <table class="table table-bordered">
                    <tr>
                        <td colspan="2"><strong>{{$data['name']}}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Phone number</strong></td>
                        <td>{{$data['phone']}}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{$data['email']}}</td>
                    </tr>
                    <tr>
                        <td><strong>Post applied for</strong></td>
                        <td>{{$data['post_applied']}}</td>
                    </tr>
                </table>
                <h1>Assessment result</h1>
                @foreach($data['question_data'] as $q_key => $question)
                <table class="table table-striped table-bordered">
                    <tr>
                        <td colspan="2"><strong>{{$question['question']}}</strong></td>
                    </tr>
                    @foreach($question['option'] as $o_key =>$option)
                    <tr>
                        @if($question['correct'] == $option)
                        <td width="50%"><strong>{{$option}}</strong></td>
                        @else
                        <td width="50%">{{$option}}</td>
                        @endif
                        <td width="50%">
                            @if($data['user_result'][$q_key]['option_select'] == $o_key)
                            User response
                            @else
                            &nbsp;
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                <br/>
                @endforeach
        </div>
    </div>
</body>
</html>