/**
 * Process functions about papers/books
 * author: Martin SangDo
 */
//========== CLASS
function AdminBook() { }

var adminBook = new AdminBook();		//global object
//get unique book id for our system db
AdminBook.prototype.generate_specific_code = function(){
    //try to get 10 random strings
    var rand_strs = [];
    for (var i=0; i<10; i++){
        rand_strs.push(common.rand_str());
    }
    var params = {
        rand_str: rand_strs.join('-')
    }
    common.ajaxPost(ADMIN_API_URI.GENERATE_SPECIFIC_CODE, params, function(resp){
        $('#txt_specific_code').val(common.rand_str()); //get any code
    }, function(err){
        $('#txt_specific_code').val(common.rand_str()); //get any code
    });
};

