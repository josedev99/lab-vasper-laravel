const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });

let array_question = [
    {
        question: "¿Siente que tiene demasiadas tareas para realizar en su jornada laboral?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Experimenta dolores de cabeza o tensión muscular debido al estrés laboral?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Se siente respetado y valorado en su lugar de trabajo?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Tiene buenas relaciones con sus compañeros de trabajo y supervisores?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Se siente satisfecho con su trabajo actual?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Logra mantener un equilibrio saludable entre su trabajo y su vida personal?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Se siente seguro en su puesto de trabajo?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Considera que su salario es justo y acorde a sus responsabilidades?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Ha experimentado cambios significativos en sus patrones de sueño debido al estrés laboral?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Se ha sentido ansioso o preocupado por cuestiones relacionadas con el trabajo?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Se ha sentido triste, deprimido o desmotivado debido a situaciones laborales?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Conoce los recursos de apoyo psicológico disponibles en su empresa o a través del seguro social?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Su empresa proporciona programas o actividades para promover el bienestar mental?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Se siente cómodo solicitando ayuda si experimenta problemas de salud mental relacionados con el trabajo?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    },
    {
        question: "¿Ha considerado buscar ayuda profesional para manejar el estrés o problemas relacionados con el trabajo?",
        answer: ['Nunca', 'Raramente', 'A veces', 'Frecuentemente', 'Siempre']
    }
];

document.addEventListener('DOMContentLoaded', (event) => {
    let component_questios = document.getElementById('component_questios');
    array_question.forEach((item, index) => {
        let id_answer = 'answer-' + index;
        let indexQuestion = index + 1;
        let card = document.createElement('div');
        let isInvalid = 'validate-item' + (index + 1);
        card.classList.add('card','mb-2','p-3',isInvalid);
        card.innerHTML = `
            <div class="card-header p-1">
                <div class="question">
                    <h4 class="m-0" style="font-size: 15px">${indexQuestion}. ${item.question}</h4>
                </div>
            </div>
            <div class="card-body p-1">
                <div class="answer" style="font-size: 14px" id="${id_answer}">
                </div>
            </div>
        `
        let answerElement = card.querySelector(`#${id_answer}`);
        item.answer.forEach((row,indice)=> {
            let nameOptionInput = 'answer_question' + (index + 1);
            let id_input = row + '-' + (index + 1) + '-' + (indice + 1);
            let answer = `
                <div class="radio icheck-peterriver">
                    <input type="radio" class="input_question" name="${nameOptionInput}" id="${id_input}" value="${row}">
                    <label for="${id_input}">${row}</label>
                </div>
            `;
            answerElement.innerHTML += answer;
        })
        component_questios.appendChild(card);
    })

    //enviar formulario
    let form_questions = document.getElementById('form_questions');
    if(form_questions){
        form_questions.addEventListener('submit', (event) => {
            event.preventDefault();
            let formData = new FormData(form_questions);
            //validacion
            for (let i = 1; i <= array_question.length; i++) {
                let nameInput = 'answer_question' + i;
                let isInvalid = 'validate-item' + i;
                let input = document.querySelector(`input[name="${nameInput}"]:checked`);
                if(input === null){
                    document.querySelector('.' + isInvalid).classList.add('question_empty');
                    Toast.fire({
                        icon: "warning",
                        title: `La pregunta #${i} es obligatoria.`
                    });return;
                }else{
                    document.querySelector('.' + isInvalid).classList.remove('question_empty');
                }
            }
            sendEncuesta();
        })
    }
})

function sendEncuesta(){
    let component_header = document.getElementById('component_header');
    let component_form = document.getElementById('component_form');

    component_header.innerHTML = `
        <h3 class="text-center" style="font-size: 20px;color:#4f5255">Valoración de Salud Mental para
                        Trabajadores en El
                        Salvador</h3>
        <p style="font-size: 14px;color:#020202">Gracias por completar esta encuesta. Su participación es valiosa para mejorar las condiciones de trabajo y el bienestar de todos los empleados.</p>
        <p class="m-0" style="font-size: 14px;color:#020202">Si necesita hablar con alguien sobre su salud mental, recuerde que hay recursos disponibles. No dude en contactar a su departamento de recursos humanos o a un profesional de la salud mental.</p>
    `;
    component_form.innerHTML = ``;
}