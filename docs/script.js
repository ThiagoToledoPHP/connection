$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "xml/structure.xml",
        dataType: "xml",
        success: function (xml) {

            //console.log($(xml).find('namespace-alias').text());
            //console.log($(xml).find('file').find('class').find('name').text());

            projectName = $(xml).find('file').children('class').children('name').text();
            projectFullName = $(xml).find('file').children('class').children('full_name').text();
            projectDescription = $(xml).find('file').children('class').children('docblock').children('description').text();
            projectTags = $(xml).find('file').children('class').children('docblock').children('tag');

            $("#projectNameLink").text(projectName);
            $("title").text(projectName);

            $("#projectFullName").text(projectFullName);
            $("#projectDescription").text(projectDescription);

            projectTags.each(function(){

                var tag = $(this);
                var tagName = tag.attr('name');
                var tagDescription = tag.attr('description');
                var tagLink = tag.attr('link');

                if(tagLink === undefined){
                    tagLink = "";
                }else{
                    tagLink = " href='" + tagLink + "' ";
                }

                $("#projectTags").append("<li class='projectTag'><a " + tagLink + " >" + tagName + "</a> " + tagDescription +"</li>");

            })


        },
        error: function () {
            alert("Error to load XML :( ");
        }
    });
});
