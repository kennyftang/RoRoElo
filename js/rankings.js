/**
 * Created by kenyunot on 27/01/2017.
 */
function addPlayer() {
    var nameInput = document.createElement("input");
    nameInput.setAttribute("type", "text");
    nameInput.setAttribute("name", "name");
    var newRow = document.createElement("tr");
    var tdInput = document.createElement("td");
    tdInput.appendChild(nameInput);
    newRow.appendChild(document.createElement("td"));
    newRow.appendChild(tdInput);
    newRow.appendChild(document.createElement("td"));
    newRow.appendChild(document.createElement("td"));
    newRow.setAttribute("id", "newplayer")
    document.getElementsByTagName("tbody").item(0).appendChild(newRow);
    var newPlayerForm = document.createElement("form");
    newPlayerForm.setAttribute("method", "post");
    //TODO: Wrap this button around a form to POST the name
    document.getElementsByTagName("button")[0].removeAttribute("onclick");
}