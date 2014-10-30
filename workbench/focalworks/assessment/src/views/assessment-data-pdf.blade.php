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
<body>
    <h1>User details</h1>
    <table border="1" cellpadding="2" cellspacing="2">
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
    @foreach($data['question_data'] as $question)
    <table border="1" cellpadding="2" cellspacing="2">
        <tr>
            <td colspan="2">{{$question['question']}}</td>
        </tr>
        @foreach($question['option'] as $option)
        <tr>
            @if($question['correct'] == $option)
            <td><strong>{{$option}}</strong></td>
            @else
            <td>{{$option}}</td>
            @endif
            <td>2</td>
        </tr>
        @endforeach
    </table>
    <br/>
    @endforeach
</body>
</html>