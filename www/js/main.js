$(document).ready(function() {
    $('[data-toggle=offcanvas]').click(function() {
        $('.row-offcanvas').toggleClass('active');
    });
});

$(function () {
    $.nette.init();
});

$("body").on("submit", "form.ajax", function () {
    $(this).ajaxSubmit();
    return false;
});

var simplemde = new SimpleMDE({
    blockStyles: {
        bold: "__",
        italic: "_"
    },
    element: document.getElementById("description-textarea"),
    forceSync: true,
    hideIcons: ["guide", "heading"],
    indentWithTabs: false,
    insertTexts: {
        horizontalRule: ["", "\n\n-----\n\n"],
        image: ["![](http://", ")"],
        link: ["[", "](http://)"],
        table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
    },
    lineWrapping: false,
    parsingConfig: {
        allowAtxHeaderWithoutSpace: true,
        strikethrough: false,
        underscoresBreakWords: true,
    },
    placeholder: "Pište sem...",
    promptURLs: true,
    renderingConfig: {
        singleLineBreaks: false,
        codeSyntaxHighlighting: true,
    },
    shortcuts: {
        drawTable: "Cmd-Alt-T"
    },
    showIcons: ["code", "table"],
    spellChecker: false,
    status: false,
    status: ["autosave", "lines", "words", "cursor"], // Optional usage
    status: ["autosave", "lines", "words", "cursor", {
        className: "keystrokes",
        defaultValue: function(el) {
            this.keystrokes = 0;
            el.innerHTML = "0 Keystrokes";
        },
        onUpdate: function(el) {
            el.innerHTML = ++this.keystrokes + " Keystrokes";
        }
    }], // Another optional usage, with a custom status bar item that counts keystrokes
    styleSelectedText: false,
    tabSize: 4
});
