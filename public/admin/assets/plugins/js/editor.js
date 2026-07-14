$(function (){
    VT.Editor = {
        init:function(){
            CKEDITOR.on("dialogDefinition", function(ev) {
                const dialogName = ev.data.name;
                const dialogDefinition = ev.data.definition;
 
                if (dialogName === "table") {
                    const info = dialogDefinition.getContents("info");
                    info.get("txtWidth")["default"] = "100%"; // Set default width to 100%
                    info.get("txtBorder")["default"] = "0"; // Set default border to 0
                    info.get("txtRows")["default"] = "1";
                    info.get("txtCols")["default"] = "1";
                }
            });
            const editors = $('textarea.editor');
            $.each(editors, function(index, editor) {
                const id = $(editor).attr("id");
                const url = $(editor).data("variable-url");
                if (url) {
                    VT.Editor.loadWithData(id, url);
                } else {
                    VT.Editor.load(id);
                }
            });
        },
        loadWithData:function(id,url){
            $.get(url).then(function(response) {
                const groups = response.groups;
                const instance = CKEDITOR.replace(id, {
                    extraPlugins:  "justify, print,tableresize,font,indentblock,colorbutton,image2,youtube",
                    height: "300px",
                    on: {
                        pluginsLoaded: function() {
                            var editor = this,
                                config = editor.config;
                            editor.ui.addRichCombo("my-combo", {
                                label: response.label,
                                title: response.title,
                                toolbar: "basicstyles,0",
 
                                panel: {
                                    css: [CKEDITOR.skin.getPath("editor")].concat(
                                        config.contentsCss
                                    ),
                                    multiSelect: false,
                                    attributes: { "aria-label": "My Dropdown Title" },
                                },
 
                                init: function() {
                                    const _this = this;
                                    Object.keys(groups).map(function(key, index) {
                                        _this.startGroup(key);
                                        Object.keys(groups[key]).map(function(display, i) {
                                            console.log(i);
                                            _this.add(groups[key][display],display);
                                        });
                                    });
                                },
                                onClick: function(value) {
                                    editor.focus();
                                    editor.fire("saveSnapshot");
                                    editor.insertHtml(" " + value + " ");
                                    editor.fire("saveSnapshot");
                                },
                            });
                        },
                    },
                });
                instance.addCommand("media", {
                    exec: function (edt) {
                        const media = $("#mediaModal");
                        media.data('editor',id);
                        media.modal('show');
                    },
                });
                instance.ui.addButton("SuperButton", {
                    label: "Media Manager",
                    command: "media",
                    toolbar: "insert",
                    icon: "image",
                });
            });
        },
        load:function(id){
            const instance = CKEDITOR.replace(id, {
                extraPlugins:
                    "justify, print,tableresize,font,indentblock,colorbutton,image2,youtube",
                height:"300px"
            });
            instance.addCommand("media", {
                exec: function (edt) {
                    const media = $("#mediaModal");
                    media.data('editor',id);
                    media.modal('show');
                },
            });
            instance.ui.addButton("SuperButton", {
                label: "Media Manager",
                command: "media",
                toolbar: "insert",
                icon: "image",
            });
        }
    }
    VT.Editor.init();



 });
 window.VT = {};

 
 