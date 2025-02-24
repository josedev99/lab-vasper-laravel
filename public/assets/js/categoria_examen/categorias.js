document.addEventListener('DOMContentLoaded', (e)=>{
    try{
        //Formulario para nuevo categoria
        let form_cat_examen = document.getElementById('form_cat_examen');
        let btn_add_cat = document.querySelector('.btn-add-cat');
        if(btn_add_cat){
            btn_add_cat.addEventListener('click', (e)=>{
                e.stopPropagation();
                $("#modal_new_categoria").modal('show');
            })
        }
        if(form_cat_examen){
            form_cat_examen.addEventListener('submit', (e)=>{
                e.preventDefault();
                let formData = new FormData(form_cat_examen);
                
                for (let [key, value] of formData.entries()) {
                    let labelTextContent = document.querySelector('label[for="'+key+'"]').textContent;
                    if (!value.trim()) {                
                        Toast.fire({
                            icon: "warning",
                            title: `El campo ${labelTextContent} es requerido.` 
                          });
                        return;
                    }
                }

                axios.post(route('cat.examen.save'),formData,{
                    headers:{
                        'Content-Type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                })
                .then((result) => {
                    if(result.data.status === "success"){
                        $("#modal_new_categoria").modal('hide');
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                          });
                        list_examenes_vista_previa();
                        let categoria_select = $("#categoria_id").selectize()[0].selectize;
                        categoria_select.addOption({
                            value: result.data.results.id,
                            text: result.data.results.nombre
                        });
                        categoria_select.addItem(result.data.results.id);
                        form_cat_examen.reset();
                    }else if(result.data.status === "exists"){
                        Toast.fire({
                            icon: "warning",
                            title: result.data.message
                          });
                    }else{
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error"
                          });
                    }
                }).catch((err) => {
                    console.log(err);
                });
            })
        }
    }catch(err){

    }
})