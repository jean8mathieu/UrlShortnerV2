!function(e){var t={};function a(r){if(t[r])return t[r].exports;var n=t[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,a),n.l=!0,n.exports}a.m=e,a.c=t,a.d=function(e,t,r){a.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="/",a(a.s=1)}({1:function(e,t,a){e.exports=a("kVNc")},RJsS:function(e,t){var a=$(".alert-message");e.exports={init:function(){this.clearAlert()},clearAlert:function(){a.html("")},disableButton:function(e){e.attr("disabled",!0),setTimeout(function(){e.attr("disabled",!1)},1e3)},setAlert:function(e){a.append(e)},generateAlert:function(e,t){return'<div class="alert alert-'+e+'" role="alert">'+t+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">    <span aria-hidden="true">&times;</span></button></div>'}}},kVNc:function(e,t,a){var r=a("RJsS");function n(e){var t=$(e).data("id"),a=$(e).data("href");confirm("Are you sure you want to delete this url ("+t+")?")&&($.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$.ajax({type:"DELETE",data:{id:t},dataType:"JSON",url:a,success:function(a){$(e).removeClass("btn-success"),$(e).removeClass("btn-danger"),$(".urlTable").find("tr[data-id='"+t+"']").html(""),r.setAlert(r.generateAlert("success",a.message)),0===$(".urlTable > tbody > tr").html().length&&$(".urlTable tbody tr").append("<td colspan='100'>There's no url</td>"),$(".urlListTable").find("tr[data-id='"+t+"']").remove(),0===$(".urlListTable > tbody > tr").length&&$(".urlListTable > tbody").append("<tr><td colspan='100'>There's no url</td></tr>")},error:function(e){r.setAlert(r.generateAlert("danger",JSON.parse(e.responseText).message))}}))}$(".deleteBan").on("click",function(){var e=$(this).data("id"),t=$(this).data("href"),a=$(this);confirm("Are you sure you want to delete this ban ("+e+")?")&&($.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$.ajax({type:"DELETE",data:{id:e},dataType:"JSON",url:t,success:function(t){a.removeClass("btn-success"),a.removeClass("btn-danger"),$('.urlBans[data-ip="'+t.value.ip+'"]').html("<a href='"+t.value.url+"' class='btn btn-danger w-100'><i class='fas fa-user-slash'></i></a>"),$(".bansTable").find("tr[data-id='"+e+"']").html(""),r.setAlert(r.generateAlert("success",t.message))},error:function(e){r.setAlert(r.generateAlert("danger",JSON.parse(e.responseText).message))}}))}),$("#searchResultModal").on("click",".deleteUrl",function(){n(this)}),$(".deleteUrl").on("click",function(){n(this)}),$(".submitForm").on("click",function(){var e=$("form");$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});var t=new FormData(e[0]);t.append("_method",e.attr("method")),$.ajax({type:"POST",url:e.attr("action"),data:t,processData:!1,contentType:!1,success:function(e){r.setAlert(r.generateAlert("success",e.message))},error:function(e){r.setAlert(r.generateAlert("danger",JSON.parse(e.responseText).message))}})}),$(".deleteForbidden").on("click",function(){var e=$(this).data("id"),t=$(this).data("href"),a=$(this).data("keyword"),n=$(this);confirm("Are you sure you want to delete the keyword ["+a+"]?")&&($.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$.ajax({type:"DELETE",data:{id:e},dataType:"JSON",url:t,success:function(t){n.removeClass("btn-success"),n.removeClass("btn-danger"),$(".forbiddenTable").find("tr[data-id='"+e+"']").html(""),r.setAlert(r.generateAlert("success",t.message))},error:function(e){r.setAlert(r.generateAlert("danger",JSON.parse(e.responseText).message))}}))}),$("#searchBtn").on("click",function(){var e=$("#search");r.disableButton($("#searchBtn")),$.ajax({type:"POST",url:"/admin/api/search",data:{search:e.val(),_token:$('meta[name="csrf-token"]').attr("content")},dataType:"JSON",success:function(t){var a=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"table-hover",a=arguments.length>2&&void 0!==arguments[2]&&arguments[2],r="";if(Array.isArray(e)&&0!==e.length){r='<table class="table '+t+' urlListTable">            <thead>            <tr>                <th>ID</th>                <th>Url</th>                <th>Click</th>                <th>IP</th>                <th></th>            </tr>            </thead>';for(var n=0;n<e.length;n++){$('meta[name="website"]').attr("content");var s=e[n].url;!0===a&&s.length>75&&(s=s.substring(0,80)+"..."),r+="<tr data-id='"+e[n].id+"'><td>"+e[n].id+"</td><td><a href='"+s+"' target='_blank'>"+s+"</a></td><td>"+e[n].click+"</td><td>"+e[n].ip+'</td><td>   <button type="button" data-id=\''+e[n].id+"' data-href='"+e[n].deleteUrl+'\' class="btn btn-warning deleteUrl">       <i class="far fa-trash-alt"></i>   </button></td></tr>'}r+="</table>"}else r+="<tr><td colspan='100' class='text-center'>There's currently no data to display...</td></tr>";return r}(t.data,"table-hover",!0);$("#searchResultTable").html(a),$("#searchResultModal").modal("show"),e.val("")},error:function(e){r.clearAlert(),void 0===e.responseText?r.setAlert(r.generateAlert("danger","Something went wrong. Please try again :(")):r.setAlert(r.generateAlert("danger",JSON.parse(e.responseText).message))},fail:function(e){r.setAlert(r.generateAlert("warning",JSON.parse(e.responseText).message))}})})}});