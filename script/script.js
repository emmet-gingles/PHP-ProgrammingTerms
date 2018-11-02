
// variable for each of the four forms
var updateForm = document.getElementById("form-updateTopic");
var createForm = document.getElementById("form-addTopic");
var addTagForm = document.getElementById("form-addTag");
var searchTagsForm = document.getElementById("form-selectTags");
// variable to determine the current form that is open
var formType;

// function that displays form for updating a topic
function showUpdateTopic(button_id) {
    var div_id = document.getElementById(button_id).parentNode.id;
    var children = document.getElementById(div_id).childNodes;
    var topic = children[3].textContent;
    var description = children[9].textContent;
    // show form and set the form fields to the values of the topic selected
    updateForm.style.display = "block";
    document.getElementById("update-item-id").value = div_id;
    document.getElementById("updateForm-topic").value = topic;
    document.getElementById("updateForm-description").value = description;
    formType = "updateTopic";
}

// function that displays form for creating a topic
function showAddTopic(){
    // show form and set the form fields to null
    createForm.style.display = "block";
    document.getElementById("create-topic").value = "";
    document.getElementById("create-description").value = "";
    formType = "addTopic";
}

// function that displays form for searching tags
function showSelectTags(){
    // show form and set the text field to null
    searchTagsForm.style.display = "block";
    document.getElementById("search").value = "";
    formType = "selectTags";
}

// function that displays form for adding a tag to a topic
function showAddTag(id){
    // we only want the number at the end of the id
    id = id.substring(7);
    // show form and set the form fields to null
    addTagForm.style.display = "block";
    document.getElementById("addTag-item-id").value = id;
    document.getElementById("suggestions").innerHTML = "";
    document.getElementById("newTag").value = "";
    formType = "addTag";
}

// function that makes a call to the server to delete a topic
function deleteTopic(button_id){
    // if "OK" is selected then delete the topic and reload the page
    if(confirm("Are you sure you want to delete this topic?")){
        var id = document.getElementById(button_id).parentNode.id;
        $.ajax({
            url: 'db_scripts/deleteTopic.php',
            type: 'POST',
            data: { id: id },
            success: function (data) {
                alert(data);
                window.location.reload();
            },
            error: function (errorMessage) {
                alert("The following error occurred: "+errorMessage);
            }
        });
    }
}

// function that makes a call to the server to remove a tag from a topic
function deleteTag(id){
    // if "OK" is selected then remove the tag from the topic and reload the page
    if(confirm("Are you sure you want to remove this tag from this topic?")){
        var id = id.substring(10);
        $.ajax({
            url: 'db_scripts/deleteTag.php',
            type: 'POST',
            data: { id: id },
            success: function (data) {
                alert(data);
                window.location.reload();
            },
            error: function (errorMessage) {
                alert("The following error occurred: "+errorMessage);
            }
        });
    }
}

// function that closes whatever the form is currently open
function closeForm(){
    var button;
    if(formType == "updateTopic"){
        updateForm.style.display = "none";
        button = document.getElementById("btn-updateTopic");
    }
    else if(formType == "addTopic") {
        createForm.style.display = "none";
        button = document.getElementById("btn-addTopic");
    }
    else if(formType == "addTag"){
        addTagForm.style.display = "none";
        button = document.getElementById("btn-addTag");
    }
    else if(formType == "selectTags"){
        searchTagsForm.style.display = "none";
        button = document.getElementById("btn-selectTags");
    }
    // disable the appropriate button and change its colour to grey
    button.disabled = true;
    button.style.backgroundColor = "grey";
    // clear the suggestions list
    $("[name='suggestions']").html("");
}

// function that enables buttons depending on the form
function enableButton() {
    var button;
    if(formType == "updateTopic"){
        button = document.getElementById("btn-updateTopic");
    }
    else if(formType == "addTopic"){
        button = document.getElementById("btn-addTopic");
    }
    else if(formType == "addTag"){
        button = document.getElementById("btn-addTag");
    }
    else if(formType == "selectTags"){
        button = document.getElementById("btn-selectTags");
    }
    // enable the appropriate button and change its colour to lawngreen
    button.disabled = false;
    button.style.backgroundColor = "lawngreen";
}


// function that set the position of the form
function setFormPositions() {
    // set variable to top of the page
    var topY = document.body.scrollTop;
    // if page has been scrolled down then set a new position for each form
    if (topY > 0) {
        var forms = document.getElementsByClassName("form");
        for (j = 0; j < forms.length; j++) {
            forms[j].style.top = topY - 50;
        }
    }
}

// function that return all the topics which match a tag
function searchTags(event){
    event.preventDefault();
    var tag = document.getElementById("search").value;
    if(tag == ""){
        alert("Cannot be null");
    }
    else{
        location.replace("index.php?tag="+tag);
    }
}

// function that sets the text of input to the value selected from the list
function setTagText(event, text) {
    document.getElementById(event.target.getAttribute('name')).value = text;
    $("[name='suggestions']").html("");
}

// if mouse click and the target is within the form then close it
window.onclick = function(event) {
    if(event.target == updateForm || event.target == createForm || event.target == addTagForm || event.target == searchTagsForm) {
        closeForm();
    }
};

// if window is scrolled then call function to update form position
window.onscroll = function () {
    setFormPositions();
};


$("document").ready(function () {
    // call function to set form position - used for AJAX reloads
    setFormPositions();

    // if input then call function to get suggested tags
    $(".searchTags").on('input propertychange', function (event) {
        getSuggestions(event);
    });

    // on button click, call function that updates the topic based on the form data
    $("#btn-updateTopic").click(function(event) {
        event.preventDefault();
        var id = document.getElementById("update-item-id").value.trim();
        var topic = document.getElementById("updateForm-topic").value.trim();
        var description = document.getElementById("updateForm-description").value;
        var isValid = true;

        if (topic == "") {
            alert("Topic cannot be null");
            isValid = false;
        }
        else if (topic.length < 3){
            alert("Topic must contain at least 3 characters");
            isValid = false;
        }
        if (description == "") {
            alert("Description cannot be null");
            isValid = false;
        }
        else if(description.length < 50){
            alert("Description must contain at least 50 characters");
            isValid = false;
        }
        if (isValid) {
            var formData = JSON.stringify({"id": id, "topic": topic, "description": description});
            $.ajax({
                url: 'db_scripts/updateTopic.php',
                type: 'POST',
                data: { data: formData },
                success: function (data) {
                    alert(data);
                    window.location.reload();
                },
                error: function (errorMessage) {
                    alert("The following error occurred: "+errorMessage);
                }
            });
        }
    })

    // on button click, call function that adds a new topic based on form data
    $("#btn-addTopic").click(function(event) {
        event.preventDefault();
        var topic = document.getElementById("create-topic").value.trim();
        var description = document.getElementById("create-description").value.trim();
        var isValid = true;

        if (topic == "" ) {
            alert("Topic cannot be null");
            isValid = false;
        }
        else if (topic.length < 3){
            alert("Topic must contain at least 3 characters");
            isValid = false;
        }
        if (description == "") {
            alert("Description cannot be null");
            isValid = false;
        }
        else if(description.length < 50){
            alert("Description must contain at least 50 characters");
            isValid = false;
        }
        if (isValid) {
            var formData = JSON.stringify({"topic": topic, "description": description});
            $.ajax({
                url: 'db_scripts/addTopic.php',
                type: 'POST',
                data: { data: formData },
                success: function (data) {
                    alert(data);
                    window.location.reload();
                },
                error: function (errorMessage) {
                    alert("The following error occurred: "+errorMessage);
                }
            });
        }
    })

    // on button click, call function that add a tag to a topic
    $("#btn-addTag").click(function(event) {
        event.preventDefault();
        var tag = document.getElementById("newTag").value.trim();
        var id = document.getElementById("addTag-item-id").value;
        if(tag.length == 0){
            alert("Tag cannot be empty")
        }
        else{
            var formData = JSON.stringify({"id": id, "tag": tag});
            $.ajax({
                url: 'db_scripts/addTag.php',
                type: 'POST',
                data: { data: formData },
                success: function (data) {
                    if(data.startsWith("ERROR:")){
                        alert(data.substring(6));
                    }
                    else{
                        alert(data);
                        window.location.reload();
                    }
                },
                error: function (errorMessage) {
                    alert("The following error occurred: "+errorMessage);
                }
            });
        }
    });


    // function that suggests tags based on user input
    function getSuggestions(event){
        // get the id of the text field
        var textId = event.target.id;
        // get the text input
        var text = document.getElementById(textId).value;
        // clear the list of suggestions
        $("[name='suggestions']").html("");

        // only offer suggestions once user has entered two characters
        if(text.length >= 2){
            $.ajax({
                url: 'db_scripts/selectTags.php',
                type: 'POST',
                data: { text: text },
                success: function (data) {
                    if(data.length > 0){
                        // split the data using a break tag to get a list of each individual tag
                        data = data.split('<br/>');
                        // for adding a tag to a topic - loop through each tag and add it to the list of suggestions
                        if(textId == 'newTag' ){
                            for(i=0;i< data.length-1;i++) {
                                $("[name='suggestions']").append("<li name='newTag' onClick='setTagText(event, $(this).text())'>" + data[i] + "</li>");
                            }
                        }
                        // for searching for a tag - loop through each tag and add it to the list of suggestions
                        else if(textId == 'search'){
                            for(i=0;i< data.length-1;i++){
                                $("[name='suggestions']").append("<li name='search' onClick='setTagText(event, $(this).text())'>"+data[i]+"</li>");
                            }
                        }
                    }
                },
                error: function (errorMessage) {
                    alert("The following error occurred: "+errorMessage);
                }
            });
        }
    }

});