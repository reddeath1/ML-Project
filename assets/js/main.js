$(document).ready(function() {

    /**
     * get ads in real time
     */

    var lastEventId;
    (function ($$,$) {
        if($('#contents_ads').element !== null){

            $('#contents_ads').html("Loading ....");
            $$.onMessage({
                meth:"get",
                url:user.url+'/core/Ajx.php',
                query:"action=getPost",
                interval:1000,
                success:function(data){
                    var result = JSON.parse(data.data);

                    if(typeof result.success !== 'undefined' && lastEventId !== result.success.lastEventId){
                        $('#contents_ads').html(result.success.result);
                    }

                    lastEventId = result.success.lastEventId;

                    steps();
                }
            });
        }

    })(Exile().http,$$);


    function steps(){
        var navListItems = $('ul.setup-panel li a'),
            allWells = $('.wr > div > div > .setup-content');

        navListItems.click(function(e) {
            e.preventDefault();

            var ul = $($(this.parentElement)).parent()[0],
                n = ul.className.replace(/[^0-9]/g,''),
                $target = $($(this).attr('href')),
                $item = $(this).closest('li'),
                allWells = $('.cont'+n+' > div > div > .setup-content');

            if (!$item.hasClass('active')) {
                $('.cont'+n+' > div > div > div > div > ul.setup-panel li').removeClass('active');
                $item.addClass('active');
                allWells.hide();
            }


            allWells.hide();
            $('.cont'+n+' > div > div > #'+$target[0].id).show();

        });



        $('.add-to-cart').on('click',function () {
            var mid = this.className.replace(/[^0-9]/g,'');
            $('.pop'+mid).modal();
        });
    }

    $(".owl-carousel").owlCarousel({
        itemsDesktop : [1499,4],
        itemsDesktopSmall : [1199,3],
        itemsTablet : [899,2],
        itemsMobile : [599,1],
        autoWidth:true,
        loop:true,
        navigation : true,
        pagination:false,
        navigationText : ['<span class="fa-stack"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-chevron-circle-left"></i></span>','<span class="fa-stack"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-chevron-circle-right fa-stack-1x fa-inverse"></i></span>'],
    });

    $('.account').on('click',function () {
        $('#login-modal').modal();
    });


    // Login signup

    var $formLogin = $('#login-form');
    var $formLost = $('#lost-form');
    var $formRegister = $('#register-form');
    var $divForms = $('#div-forms');
    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 2000;

    $("form").submit(function () {
        switch(this.id) {

            case "login-form":
                var $lg_username=$('#login_username').val();
                var $lg_password=$('#login_password').val();
                if ($lg_username.length < 2 || $lg_password.length < 2) {
                    msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'), "error", "glyphicon-remove", "Login Failed: Invalid login credentials! ");
                } else {
                    login({u:$lg_username,p:$lg_password});
                }
                return false;
                break;


            case "lost-form":
                var $ls_email=$('#lost_email').val();
                if ($ls_email == "ERROR") {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "error", "glyphicon-remove", "Send error");
                } else {
                    msgChange($('#div-lost-msg'), $('#icon-lost-msg'), $('#text-lost-msg'), "success", "glyphicon-ok", "Send OK");
                }
                return false;
                break;


            case "register-form":
                var rg_fn=$('#register_fn').val();
                var rg_ln =$('#register_ln').val();
                var rg_tel =$('#register_tel').val();
                var rg_email=$('#register_email').val();
                var rg_password=$('#register_password').val();
                if (rg_fn.length > 1 && rg_ln.length > 1 && rg_tel.length > 9 && rg_email.length > 5 && rg_password.length > 2) {
                    register({fn:rg_fn,ln:rg_ln,tel:rg_tel,e:rg_email,p:rg_password});
                } else {

                    var e = '';
                    if(rg_fn.length < 2 || rg_ln.length < 2) {
                        e = "Enter a valid user name!";
                    }
                    if(rg_tel.length < 10) {
                        e = "Invalid phone number!";
                    }
                    if(rg_password.length < 2) {
                        e = "Password is not strong!";
                    }
                    msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "error", "glyphicon-remove", "Register error: "+e);
                }
                return false;
                break;

            default:
                return false;
        }
        return false;
    });

    function register(obj){
        var fn = obj.fn,
        ln = obj.ln,
        tel = obj.tel,
        e = obj.e,
        p = obj.p;
        $('#reg').text('Please wait ........');

        $.ajax({
            url:user.url+'/core/Ajx.php',
            type:"POST",
            data:{fn:fn,ln:ln,tel:tel,e:e,p:p,action:'signup'},
            success:function (va) {
              var result = JSON.parse(va);

              if(typeof result.success !== 'undefined'){
                  msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "success", "glyphicon-ok", result.success);
                  setTimeout(function () {
                      window.location.reload(true);
                  },2000);
              }else{
                  $('#reg').text('Register');
                  msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "error", "glyphicon-remove", result.error);
              }
            }
        });
    }

    function login(obj){
        $('#log').text('Please wait ....');
        
        $.ajax({
            url: user.url+'/core/Ajx.php',
            type:'post',
            data:{action:'login',u:obj.u,p:obj.p},
            success:function(va){
                var result = JSON.parse(va);

                if(typeof result.success !== 'undefined'){
                    msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "success", "glyphicon-ok", result.success);
                    setTimeout(function () {
                        window.location.reload(true);
                    },1000);
                }

                if(typeof result.error !== 'undefined'){
                    $('#log').text('Login');
                    msgChange($('#div-register-msg'), $('#icon-register-msg'), $('#text-register-msg'), "error", "glyphicon-remove", result.error);
                }


            }
        });
        msgChange($('#div-login-msg'), $('#icon-login-msg'), $('#text-login-msg'), "success", "glyphicon-ok", "Login OK");
    }

    $('#login_register_btn').click( function () { modalAnimate($formLogin, $formRegister) });
    $('#register_login_btn').click( function () { modalAnimate($formRegister, $formLogin); });
    $('#login_lost_btn').click( function () { modalAnimate($formLogin, $formLost); });
    $('#lost_login_btn').click( function () { modalAnimate($formLost, $formLogin); });
    $('#lost_register_btn').click( function () { modalAnimate($formLost, $formRegister); });
    $('#register_lost_btn').click( function () { modalAnimate($formRegister, $formLost); });

    function modalAnimate ($oldForm, $newForm) {
        var $oldH = $oldForm.height();
        var $newH = $newForm.height();
        $divForms.css("height",$oldH);
        $oldForm.fadeToggle($modalAnimateTime, function(){
            $divForms.animate({height: $newH}, $modalAnimateTime, function(){
                $newForm.fadeToggle($modalAnimateTime);
            });
        });
    }

    function msgFade ($msgId, $msgText) {
        $msgId.fadeOut($msgAnimateTime, function() {
            $(this).text($msgText).fadeIn($msgAnimateTime);
        });
    }

    function msgChange($divTag, $iconTag, $textTag, $divClass, $iconClass, $msgText) {
        var $msgOld = $divTag.text();
        msgFade($textTag, $msgText);
        $divTag.addClass($divClass);
        $iconTag.removeClass("glyphicon-chevron-right");
        $iconTag.addClass($iconClass + " " + $divClass);
        setTimeout(function() {
            msgFade($textTag, $msgOld);
            $divTag.removeClass($divClass);
            $iconTag.addClass("glyphicon-chevron-right");
            $iconTag.removeClass($iconClass + " " + $divClass);
        }, $msgShowTime);
    }

    console.log(user);
});

function issueInvoice() {
    alert("Working on it!");
}

function deleteInvoice() {
    alert("Working on it!");
}

function save(id,e) {
    if(!user.isLoggedIn) { $('#login-modal').modal(); return false }

    var filter = $(".filter"+id).val(),
        num = $('.number'+id).val();

    $(e).text('Please wait ....');

    $.ajax({
        url: user.url+'/core/Ajx.php',
        type:'post',
        data:{action:'saveAd',ad:id,dim:filter,u:user.u},
        success:function(va){
            var result = JSON.parse(va),u = user.un.replace(' ','-');

            if(typeof result.success !== 'undefined'){
                $(e).text('Saved');
                $(e.parentElement).html("<a href='"+user.url+"/dashboard"+user.u+"'>View Cart</a>");
            }

            if(typeof result.error !== 'undefined'){
                $(e).text('save');
                $('.status').html(result.error);
            }

        }
    });
}

