
$(document).ready( function () {

    /**
     * code JS pour vérifier les erreurs formulaires
     * @type {RegExp}
     */
    const passwordRegex = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$^+=!*()@%&]).{8}$/);
    const mailRegex = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/);
    let noErrorsFormUser= true;
    let noErrorsFormPassword = true;




    /**
     * Vérifie si le username est unique
     */
    $('#user_username').blur(function(){
        const nomError = document.getElementById('nomError');
        const submitButton = document.getElementById('formUserSubmit');
        const username = document.getElementById('user_username');
        let usernameValue = $('#user_username').val();
        if(usernameValue!==''){
            $.ajax({
                type: 'POST',
                url: 'check',
                data : {username: usernameValue},
                async : true,
                dataType:`json`,
            }).done(function(data){
                let user = JSON.parse(data);
                if(user === null){
                    validInvalidCss(username,true);
                    noErrorsFormUser = false;
                    disabledEnabledButtonAndError(submitButton,nomError,true, noErrorsFormUser, noErrorsFormPassword);
                }else{
                    validInvalidCss(username,false);
                    disabledEnabledButtonAndError(submitButton,nomError,false,noErrorsFormUser,noErrorsFormPassword);
                }
            })
        }
    })
    /**
     * toast
     */
    $('.toast').toast('show');

    /**
     * vérifie le password/Regex
     */
    $('#user_password').on('keyup',function(e){
        checkPassword(passwordRegex,noErrorsFormUser,noErrorsFormPassword)
    })
    /**
     * Vérifie le repeatPassword
     */
    $('#repeatPassword').on('keyup',function(e){
        checkPassword(passwordRegex,noErrorsFormUser,noErrorsFormPassword)
    })

    }
)

/**
 * Vérifie le password et le confirmPassword
 * @param passwordRegex
 * @param noErrorsFormUser
 * @param noErrorsFormPassword
 */
function checkPassword(passwordRegex,noErrorsFormUser,noErrorsFormPassword){


}

/**
 * afficher ou cacher les erreurs
 * @param button
 * @param error
 * @param enable
 * @param noErrorsFormUser
 * @param noErrorsFormPassword
 */
 function disabledEnabledButtonAndError(button,error,enable,noErrorsFormUser, noErrorsFormPassword){
    if(enable){
        error.hidden = true;
        if(noErrorsFormPassword && noErrorsFormUser)
            button.disabled = false;
    }else{
        button.disabled = true;
        error.hidden = false;
    }
 }

/**
 * change la class de l'input
 * @param input
 * @param isValid
 * @param removeAll
 */
 function validInvalidCss(input,isValid,removeAll= false){
     if(removeAll){
         input.classList.remove('is-valid');
         input.classList.remove('is-invalid');
     }else if(isValid){
         input.classList.add('is-valid');
         input.classList.remove('is-invalid');
     }else{
         input.classList.add('is-invalid');
         input.classList.remove('is-valid');
     }
 }
