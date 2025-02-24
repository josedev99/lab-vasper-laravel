
/**
 * Validacion de signos vitales y medidas
*/
try{
    let frec_cardiaca = document.querySelector('input[name="signo_vital_fc"]');
    let frec_respiratoria = document.querySelector('input[name="signo_vital_fr"]');
    let presion_art = document.querySelector('input[name="signo_vital_pa"]');
    let temperatura = document.querySelector('input[name="medida_temp"]');
    let saturacion_oxi = document.querySelector('input[name="signo_vital_saturacion"]');

    if(frec_cardiaca && frec_respiratoria && presion_art && temperatura && saturacion_oxi){

        frec_cardiaca.addEventListener('keyup', (e) => {
            if(parseInt(e.target.value) >= 60 && parseInt(e.target.value) <= 100){
                frec_cardiaca.style.border = "2px solid #198754";
            }else{
                frec_cardiaca.style.border = "2px solid #dc3545";
            }
        })
        frec_respiratoria.addEventListener('keyup', (e) => {
            if(parseInt(e.target.value) >= 12 && parseInt(e.target.value) <= 20){
                frec_respiratoria.style.border = "2px solid #198754";
            }else{
                frec_respiratoria.style.border = "2px solid #dc3545";
            }
        })
        presion_art.addEventListener('keyup', (e) => {
            let array_val = e.target.value.split('/');
            if(array_val.length > 1){
                if(parseInt(array_val[0]) <= 120 && parseInt(array_val[1]) <= 80){
                    presion_art.style.border = "2px solid #198754";
                }else{
                    presion_art.style.border = "2px solid #dc3545";
                }
            }
        })
        temperatura.addEventListener('keyup', (e) => {
            if(parseFloat(e.target.value) >= 36.5 && parseFloat(e.target.value) <= 37){
                temperatura.style.border = "2px solid #198754";
            }else{
                temperatura.style.border = "2px solid #dc3545";
            }
        })
        saturacion_oxi.addEventListener('keyup', (e) => {
            if(parseInt(e.target.value) >= 95 && parseInt(e.target.value) <= 100){
                saturacion_oxi.style.border = "2px solid #198754";
            }else{
                saturacion_oxi.style.border = "2px solid #dc3545";
            }
        })
    }

    /**
     * Calcular IMC
     * PESO en Kg /(M)**2
     */
    let inputPeso = document.querySelector('input[name="medida_peso"]');
    let inputTalla = document.querySelector('input[name="medida_talla"]');
    if(inputPeso && inputTalla){
        inputPeso.addEventListener('keyup', (e) => {
            if(e.target.value !== ""){
                calcularImc(e.target.value,inputTalla.value,'medida_imc');
            }else{
                calcularImc(0,0,'medida_imc');
            }
        });

        inputTalla.addEventListener('keyup', (e) => {
            if(e.target.value !== ""){
                calcularImc(inputPeso.value, e.target.value,'medida_imc');
            }else{
                calcularImc(0,0,'medida_imc');
            }
        });
    }
    /**
     * Validacion para PA(ps/pd)
     */
    let signo_vital_pa = document.querySelector('input[name="signo_vital_pa"]');
    if(signo_vital_pa){
        signo_vital_pa.addEventListener('keyup', (e) => {
            let val = e.target.value;
            const regex = /\//;
            if(val.length === 3 && !regex.test(val) && (e.key !== "Backspace" || e.keyCode !== 8)){
                signo_vital_pa.value = val + '/';
            }
        })
    }

}catch(err){
    console.log(err);
}

/**
 * Calcular IMC
 */
function calcularImc(pesoKg,tallaCm,inputName){
    let tallaMts = parseFloat(tallaCm) * (1/100);
    let result = parseFloat(pesoKg) / Math.pow(tallaMts,2);
    result = (isNaN(result)) ? 0 : result;
    result = (result === Infinity) ? 0 : result;
    
    let inputIMC = document.querySelector('input[name="'+inputName+'"]');
    inputIMC.value = result.toFixed(2);
    //validaciones
    if(parseFloat(result) < 18.5){
        inputIMC.style.border = '2px solid #dc3545';
    }else if(parseFloat(result) >= 18.5 && parseFloat(result) <= 24.5){
        inputIMC.style.border = '2px solid #198754';
    }else if(parseFloat(result) >= 25 && parseFloat(result) <= 29.9){
        inputIMC.style.border = '2px solid #ffc107';
    }else if(parseFloat(result) >= 30){
        inputIMC.style.border = '2px solid #dc3545';
    }
}