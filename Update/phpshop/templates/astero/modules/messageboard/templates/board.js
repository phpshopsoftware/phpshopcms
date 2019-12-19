function modBoardCheck()
{
var s1=window.document.forms.forma_gbook.name_new.value;
var s2=window.document.forms.forma_gbook.tema_new.value;
var s3=window.document.forms.forma_gbook.content_new.value;
var s4=window.document.forms.forma_gbook.tel_new.value;
if (s1=="" || s2=="" || s3=="" || s4=="")
 alert("ќшибка заполнени€ формы объ€влени€!");
   else
     document.forma_gbook.submit();
}