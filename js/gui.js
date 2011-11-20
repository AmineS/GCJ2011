/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var host = "http://localhost/GCJ2011/";

function exReset()
{
    var url = host + "reset.php";
    
    $.post(url, function()
    {
        alert('The system has been successfully reset');
    });
    
}

function exSnapshot()
{
    
}

function exVisual()
{
    
}

function exAttachButtonEvents()
{
    $("#reset").click(exReset);
    $("#snapshot").click(exSnapshot);
    $("#visual").click(exVisual);
}

function exMain()
{
    
    exAttachButtonEvents();
}

$(document).ready(exMain);