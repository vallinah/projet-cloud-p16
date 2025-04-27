function Display(divId){
    var div=document.getElementById(divId);
    if(div){
        if(div.style.opacity=="1"){
            div.style.opacity="0";
        }else{
            div.style.opacity="1";
        }
    }else{
        alert("tsy thita");
    }
    
}
function disp_menu(id) {
    var div = document.getElementById(id);
    if (div) {
        // Vérifie le display actuel en prenant en compte les styles CSS
        var currentDisplay = getComputedStyle(div).display;

        if (currentDisplay == "none") {
            div.style.display = "block"; // Ou un autre display adapté (flex, grid, etc.)

        } else {
            div.style.display = "none";
        }
    } else {
        alert("tsy hita"); // Correction de l'orthographe
    }
}