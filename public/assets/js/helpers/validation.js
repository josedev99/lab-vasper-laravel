function validateNumberTel(identificador){
    try{
        let exp_valid_number = /^[0-9\-]+$/;
        let input = document.querySelector('input[name="'+identificador+'"]');
        if(input){
            input.addEventListener('keyup', (e)=>{
                if(exp_valid_number.test(e.target.value.trim())){
                    input.value = e.target.value;
                }else{
                    let validValue = e.target.value.trim().split('').filter(char => exp_valid_number.test(char)).join('');
                    input.value = validValue;
                }
            })
        }
    }catch(err){
        console.log(err)
    }
}