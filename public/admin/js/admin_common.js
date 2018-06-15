/**
 * Process common functions of Admin, used in every Admin pages
 * author: Martin SangDo
 */
//========== CLASS
function AdminCommon() { }

var adminCommon = new AdminCommon();		//global object
//logout
AdminCommon.prototype.logout = function(){
    common.ajaxPost(ADMIN_API_URI.LOGOUT, {}, function(resp){
        //correct authentication
        common.redirect(ADMIN_CONTROLLER+'login');
    }, function(err){
        //show alert
        alert('Something wrong when logout, try it later');
    });
}
//==========
$(document).on('ready', function () {
    //todo: get new requests

    //todo: get new messages

});