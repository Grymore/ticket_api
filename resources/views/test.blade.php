<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<input id="myText"  class = "input" type="text"  onkeyup="btnActivation()">
<input id="myText1" class = "input" type="text"  onkeyup="btnActivation()">
<input id="myText2" class = "input" type="text"  onkeyup="btnActivation()">
    <input type="button" value="Click to begin!" id="start_button" disabled/> 

    <script type="text/javascript">


        function btnActivation(){
           var input = document.getElementById("myText").value.length
           var inputt = document.getElementById("myText1").value.length
           
            if(input && inputt){
                document.getElementById("start_button").disabled = false;            
            }else{
                document.getElementById("start_button").disabled = true;

            }           
        }   

    </script>

</html>