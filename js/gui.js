/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


host="http://localhost/"

function exReset()
{
    alert('reset');
}

function exSnapshot()
{
    
}

function exVisual()
{
    
}

function exAttachButtonEvents()
{
    $("#reset").click(function(){
        exReset();
    });
    $("#snapshot").click(exSnapshot);
    $("#visual").click(exVisual);
    alert('hooooo');
}

function exMain()
{
    
    exAttachButtonEvents();
}

function exReset()
{
    url = host + "reset.php";
    $("#resetView").post(url);
}

$(document).ready(exMain);