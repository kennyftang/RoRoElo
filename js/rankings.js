/**
 * Created by kenyunot on 27/01/2017.
 */
function addPlayer() {
    //Form for the input
    var nameForm = document.createElement("form");
    //Input for entering name
    var nameInput = document.createElement("input");
    //Button for submitting name
    var submitFormButton = document.createElement("button");
    //Row to be inserted to table
    var newRow = document.createElement("tr");
    //td for the input
    var tdInput = document.createElement("td");
    //td for the button
    var tdButton = document.createElement("td");
    nameForm.setAttribute("id", "nameform");
    nameForm.setAttribute("method", "post");
    nameForm.appendChild(nameInput);
    nameInput.setAttribute("type", "text");
    nameInput.setAttribute("name", "name");
    submitFormButton.setAttribute("type", "submit");
    submitFormButton.setAttribute("form", "nameform");
    submitFormButton.innerText = "Add";
    newRow.appendChild(document.createElement("td"));
    newRow.appendChild(tdInput);
    newRow.appendChild(tdButton);
    newRow.appendChild(document.createElement("td"));
    newRow.setAttribute("id", "newplayer");
    tdButton.appendChild(submitFormButton);
    tdInput.appendChild(nameForm);
    //Insert the new row into the table
    document.getElementsByTagName("tbody").item(0).appendChild(newRow);
    //
    var newPlayerForm = document.createElement("form");
    newPlayerForm.setAttribute("method", "post");
    //TODO: Wrap this button around a form to POST the name
    var button = document.getElementsByClassName("add")[0];
    button.parentNode.removeChild(button);
}
function removePlayer() {
    var person = window.prompt("Rank #");
    var removeButton = document.getElementsByClassName("remove").item(0);
    removeButton.setAttribute("name", "removerank");
    removeButton.setAttribute("value", person);
    removeButton.setAttribute("type", "submit");
}