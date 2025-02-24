function fullScreenImage(element) {
    var modal = document.getElementById("modal_fullscreen");

    var modalImg = document.getElementById("preview_image_fullscreen");
    var captionText = document.getElementById("caption");

    modal.style.display = "block";
    modalImg.src = element.src;
    captionText.innerHTML = element.alt;

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }
}

function fullScreenImagePostIncap(element) {
    var modal = document.getElementById("modal_fullscreen_post_incap");

    var modalImg = document.getElementById("preview_image_fullscreen_post_incap");
    var captionText = document.getElementById("caption_post_incap");

    modal.style.display = "block";
    modalImg.src = element.src;
    captionText.innerHTML = element.alt;

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close_post_incap")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }
}