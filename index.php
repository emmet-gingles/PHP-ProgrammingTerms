<html>
	<head>
		<title>Programming Terms</title>
		<meta charset="windows-1252">
		<link type="text/css" rel="stylesheet" href="style/style.css">
	</head>
	<body>
		<h3>Programming Terms</h3>
        <br>
        <button id="btn-showAddTopic" onclick="showAddTopic()" class="create">Add Topic</button>
        <button id="btn-showSelectTags" onclick="showSelectTags()" class="search">Search Tags</button>
        <br>
        <?php

        require_once "db/connection.php";

        $conn = new mysqli($server, $username, $password, $database);

        if($conn -> connect_error) {
            die("Connection failed: " . $conn -> connect_error);
        }

        // if a tag has been passed then we only want topics that match the tag
        if(isset($_GET["tag"])){
            $tag = $_GET["tag"];
            // we want each unique topic that matches the tag
            $sql = "SELECT distinct(topic), description, id FROM topics INNER JOIN tags ON topics.id = tags.topicId WHERE tags.tag LIKE '$tag%' ORDER BY topic ASC";
            $res1 = $conn -> query($sql);

            // we want every row from the tags table that matches the tag
            $sql2 = "SELECT * FROM tags INNER JOIN topics ON topics.id = tags.topicId WHERE tag LIKE '$tag%'";
            $res2 = $conn -> query($sql2);
            ?>
            <br/>
            <span><b><?php echo $res1 -> num_rows; ?> topics with the tag <?php echo $tag; ?></b></span>
        <?php
        }
        // else we want all topics
        else {
            // get all topics from database
            $sql = "SELECT * FROM topics ORDER BY topic ASC";
            $res1 = $conn -> query($sql);

            // get all tags from database
            $sql2 = "SELECT * FROM tags INNER JOIN topics ON topics.id = tags.topicId";
            $res2 = $conn ->  query($sql2);
        }

        // create an array to store each tag, loop through results set and append all data for each tag
        $tags = array();
        while($r = $res2 -> fetch_assoc()){
            array_push($tags, [ "topicId" => $r["id"], "tagId" => $r["tagId"], "tag" => $r["tag"] ]);
        }


        if($res1 -> num_rows > 0 ){
            // loop through topics results set
            while($row = $res1 -> fetch_assoc()){
                    ?>
                    <!-- Each topic is displayed within a div with its name, description and list of tags.
                    Also within the div are buttons to edit, delete or add a tag to the topic
                    -->
                    <div id="<?php echo $row["id"]; ?>" class="content">
                        <label><b>Topic: </b></label>
                        <span id="topic-<?php echo $row["id"]; ?>"><?php echo $row["topic"]; ?></span>
                        <br/>
                        <label><b>Description: </b></label>
                        <span id="description-<?php echo $row["id"]; ?>"><?php echo $row["description"]; ?></span>
                        <br/>
                        <button id="edit-<?php echo $row["id"]; ?>" class="update" onclick="showUpdateTopic(this.id)">Edit
                        </button>
                        <button id="delete-<?php echo $row["id"]; ?>" class="delete" onclick="deleteTopic(this.id)">
                            Delete
                        </button>

                        <br/>
                        <label><b>Tags: </b></label>
                        <?php
                        // loop through each tag and get the key
                        foreach ($tags as $key => $value) {
                            // variable that determines whether or not to show the tag. Default value is false
                            $showTag = false;
                            // loop through each key and get the value
                            foreach($value as $s_key => $s_value) {
                                // if the key is topicId and the value is equal to the current topicId then set variable to true
                                if($s_key == "topicId" && $s_value == $row["id"]) {
                                    $showTag = true;
                                }
                                // if the key is tagId and variable is true then set tagId to the value
                                if($s_key == "tagId" && $showTag){
                                    $tagId = $s_value;
                                }
                                // if the key is tag and variable is true then show the tag
                                if($s_key == "tag" && $showTag){
                                    ?>
                                    <span id=<?php echo $tagId; ?> class="tag"> <?php echo $s_value; ?>
                                        <button id="removeTag-<?php echo $tagId; ?>" class="removeTag"  onClick="deleteTag(this.id)">&times</button>
                                    </span>
                                    <?php
                                }
                            }
                        }
                        ?>
                        <br/>
                        <button id="addTag-<?php echo $row["id"]; ?>" class="create" onclick="showAddTag(this.id)">Add Tag</button>
                    </div>

                    <?php
            }
        }
        ?>

        <!--
        The following four divs contain forms for different purposes. The divs are invisible until the appropriate button is pressed to display them
        -->
		<div id="form-updateTopic" class="form">
			<div class="form-content">
				<span class="close" onclick="closeForm()">&times</span>
				<form>
                    <input type="hidden" id="update-item-id"/>
					<label>Topic</label>
					<input type="text" id="updateForm-topic" onchange="enableButton()"/>
					<br/><br/>
					<label>Description</label>
					<textarea id="updateForm-description" onchange="enableButton()" rows="4" cols="50"></textarea>
					<br/>
					<button id="btn-updateTopic" disabled>Update</button>
				</form>
			</div>
		</div>
        <div id="form-addTopic" class="form">
            <div class="form-content">
                <span class="close" onclick="closeForm()">&times</span>
                <form>
                    <label>Topic</label>
                    <input type="text" id="create-topic" onchange="enableButton()" />
                    <br/><br/>
                    <label>Description</label>
                    <textarea id="create-description" onchange="enableButton()" rows="4" cols="50"></textarea>
                    <br/>
                    <button id="btn-addTopic" disabled>Create</button>
                </form>
            </div>
        </div>
        <div id="form-addTag" class="form">
            <div class="form-content">
                <span class="close" onclick="closeForm()">&times</span>
                <form>
                    <input type="hidden" id="addTag-item-id"/>
                    <label>Enter tag</label>
                    <input type="text" id="newTag" onchange="enableButton()" class="searchTags"/>
                    <button id="btn-addTag" disabled>Add Tag</button>
                    <br/>
                    <ul id="suggestions" class="suggestions" name="suggestions">
                    </ul>
                </form>
            </div>
        </div>

        <div id="form-selectTags" class="form">
            <div class="form-content">
                <span class="close" onclick="closeForm()">&times</span>
                <form method="POST" name="form">
                    <label>Search tags</label>
                    <input type="text" id="search" name="search" class="searchTags" onchange="enableButton()"/>
                    <button name="btn-selectTags" id="btn-selectTags" onclick="searchTags(event)" disabled>Search</button>
                    <br>
                    <ul id="suggestions" class="suggestions" name="suggestions">
                    </ul>
                </form>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="script/script.js">
        </script>
	</body>
</html>
