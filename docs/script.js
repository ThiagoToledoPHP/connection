$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "xml/structure.xml",
        dataType: "xml",
        success: function (xml) {

            //console.log($(xml).find('namespace-alias').text());
            //console.log($(xml).find('file').find('class').find('name').text());

            var projectName = $(xml).find('file').children('class').children('name').text();
            var projectFullName = $(xml).find('file').children('class').children('full_name').text();
            var projectDescription = $(xml).find('file').children('class').children('docblock').children('description').text();
            var projectTags = $(xml).find('file').children('class').children('docblock').children('tag');
            var methods = $(xml).find('file').children('class').children('method');
            var larguraJanela = $(window).width();

            //alert(larguraJanela);

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
                var methodLongDescription = method.children('docblock').children('long-description').text();
                var methodTags = method.children('docblock').children('tag');

                //Public Methods links - top
                if(visibility==="public"){


                    if(i != 0){
                        if(larguraJanela>400) {
                            htmlPublicMethods += ", ";
                        }else{
                            htmlPublicMethods += "<br>";
                        }
                    }

                    var resto = (i + 1) % 2;
                    var classeCss = "";
                    if(resto == 0){
                        classeCss = "public_method_link";
                    }else{
                        classeCss = "public_method_link2";
                    }

                    htmlPublicMethods += " <a href='#" + methodName + "' class='" + classeCss + "' >" + methodName + "</a>";

                    i++;
                }

                //methodsBlock
                if(visibility==="public"){

                    var methodReturn = "void";

                    htmlMethodsBlock += "<div class='methodElement'>";
                        htmlMethodsBlock += "<a name='" + methodName + "'></a>";
                        htmlMethodsBlock += "<h2 class='methodElementName'>" + methodName + "<a href='#' class='ancora_top'>^</a> </h2> ";
                        htmlMethodsBlock += "<p class='methodElementNameFullName'>" + methodFullName + "</p>";
                        htmlMethodsBlock += "<p class='methodDescription'>" + methodDescription + "</p>";
                        htmlMethodsBlock += "<p class='methodLongDescription'>" + linkify(methodLongDescription) + "</p>";
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
            $("#mainBlock").fadeIn("slow");

            if(larguraJanela>400) {
                $(".ancora_top").fadeIn("slow");
            }

        },
        error: function () {
            alert("Error to load XML :( ");
        }
    });
});

//Thanks http://goo.gl/G77kjA
function linkify(text) {
    var urlRegex =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    return text.replace(urlRegex, function(url) {
        return '<a href="' + url + '" target="_blank">' + url + '</a>';
    });
}
