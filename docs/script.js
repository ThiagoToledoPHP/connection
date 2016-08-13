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
            methods = $(xml).find('file').children('class').children('method');

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

                if(tagName !== "link"){
                    $("#projectTags").append("<li class='projectTag'><a " + tagLink + " >" + tagName + "</a> " + tagDescription +"</li>");
                }else{
                    $("#projectTags").append("<li class='projectTag'><a " + tagLink + " >" + tagName + "</a></li>");
                }
            })

            var htmlPublicMethods = "";
            var htmlMethodsBlock = "";
            i = 0;
            methods.each(function(){

                var method = $(this);
                var visibility = method.attr("visibility");
                var methodName = method.children('name').text();
                var methodFullName = method.children('full_name').text();
                var methodDescription = method.children('docblock').children('description').text();
                var methodTags = method.children('docblock').children('tag');

                //Public Methods links - top
                if(visibility==="public"){

                    if(i != 0){
                        htmlPublicMethods += ", &nbsp;&nbsp;&nbsp;";
                    }

                    htmlPublicMethods += "<a href='#" + methodName + "' class='public_method_link' >" + methodName + "</a>";

                    i++;
                }

                //methodsBlock
                if(visibility==="public"){

                    var methodReturn = "void";

                    htmlMethodsBlock += "<div class='methodElement'>";
                        htmlMethodsBlock += "<h2 class='methodElementName'>" + methodName + " <a name='" + methodName + "'></a></h2>";
                        htmlMethodsBlock += "<p class='methodElementNameFullName'>" + methodFullName + "</p>";
                        htmlMethodsBlock += "<p class='methodDescription'>" + methodDescription + "</p>";
                        htmlMethodsBlock += "<ul>";

                    methodTags.each(function(){
                            var tag = $(this);
                            var tagName = tag.attr("name");
                            var tagType = tag.attr("type");

                            if(tagName == "param") {
                                var tagVariable = tag.attr("variable");
                                htmlMethodsBlock += "<li>Param: <span class='methodDescriptionParamType'>" + tagType + "</span> <b class='methodDescriptionParamName' >" + tagVariable + "</b></li>";
                            }else{
                                methodReturn = tagType;
                            }
                    });

                        htmlMethodsBlock += "</ul>";
                        htmlMethodsBlock += "<p>Return: <span class='methodDescriptionParamType'>" + methodReturn + "</span></p>";
                    htmlMethodsBlock += "</div>";

                }

            });

            $("#publicMethods").append(htmlPublicMethods);
            $("#methodsBlock").append(htmlMethodsBlock);


        },
        error: function () {
            alert("Error to load XML :( ");
        }
    });
});
