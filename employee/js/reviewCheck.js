Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}

function checkRating(inputElement){
    if(inputElement.value == "" || inputElement.value == null){
        inputElement.nextSibling.nextSibling.innerHTML = "";
        return true;
    } else if (isNaN(inputElement.value)){
        inputElement.nextSibling.nextSibling.innerHTML = "" + inputElement.previousSibling.previousSibling.innerHTML + " has to be a number.";
        return false;
    } else {
        if(!(inputElement.value >=1 && inputElement.value <=5)){
            inputElement.nextSibling.nextSibling.innerHTML = "" + inputElement.previousSibling.previousSibling.innerHTML + " has to be between 1 and 5.";
            return false;
        } else {
            inputElement.nextSibling.nextSibling.innerHTML = "";
            return true;
        }
    }
}

function checkAction(inputElement){
    if(inputElement.value == "" || inputElement.value == null || inputElement.value == "N"){
        inputElement.nextSibling.nextSibling.innerHTML = "";
        return true;
    } else if (isNaN(inputElement.value) && inputElement.value != "N"){
        inputElement.nextSibling.nextSibling.innerHTML = "Action required has to be either 1 to 18 or N for no action.";
        return false;
    } else {
        if(!(inputElement.value >=1 && inputElement.value <=18)){
            inputElement.nextSibling.nextSibling.innerHTML = "Action required has to be between 1 and 18.";
            return false;
        } else {
            inputElement.nextSibling.nextSibling.innerHTML = "";
            return true;
        }
    }
}

function checkForm(){
    var ratingElements = new Array();
    var rateOfElements = new Array();

    var jobKnowledge = document.getElementById("jobKnowledge");
    ratingElements.push(jobKnowledge);

    var workQuality = document.getElementById("workQuality");
    ratingElements.push(workQuality);

    var initiative = document.getElementById("initiative");
    ratingElements.push(initiative);

    var communication = document.getElementById("communication");
    ratingElements.push(communication);

    var dependability = document.getElementById("dependability");
    ratingElements.push(dependability);

    for(var inputElements=0; inputElements<=ratingElements.length-1; inputElements++){
        var ratingOK = checkRating(ratingElements[inputElements]);
        rateOfElements.push(ratingOK);
    }

    var action = document.getElementById("actionRequired");
    var actionOK = checkAction(action);

    if(rateOfElements.contains(false) || !actionOK){
        return false;
    } else {
        return true;
    }
}