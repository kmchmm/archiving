const sign_in_btn = document.querySelector('#sign-in-btn');
const sign_up_btn = document.querySelector('#sign-up-btn');
const container_reg_log = document.querySelector('.container_reg-log');
sign_up_btn.addEventListener('click', () =>{
    container_reg_log.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener('click', () =>{
    container_reg_log.classList.remove("sign-up-mode");
});


function registerCreds(){
    var pWord = document.forms['registerForm']['password'].value;
    var cpWord = document.forms['registerForm']['cpassword'].value;

    if(pWord != cpWord){
        alert("Password does not match!");
    }
}
