function changeImage(imageName) {
    const imageElement = document.getElementById('categoryImage');
    imageElement.src = imageName;
}

function resetImage() {
    const imageElement = document.getElementById('categoryImage');
    imageElement.src = './assets/images/1.jpg';
}