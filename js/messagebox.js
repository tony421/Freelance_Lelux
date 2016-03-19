(function ($) {
    $.messagebox = {
        alert: function (options) {
            try {
                var _options = $.extend({}, $.messagebox.defaults, options);
                ShowMessagebox(_options, $.messagebox.buttonType.ok);
            } catch (e) {
                //log(e.message);
            }
        }
        , confirm: function (options) {
            try {
                var _options = $.extend({}, $.messagebox.defaults, options);
                ShowMessagebox(_options, $.messagebox.buttonType.yesno);
            } catch (e) {
                //log(e.message);
            }
        }
    };

    $.messagebox.defaults = {
        message: '',
        code: '',
        icon: 'warning', // info, warning, question, error
        defaultButton: 0,
        ok: 'OK',
        yes: 'Yes',
        no: 'No',
        onOK: function () { },
        onYes: function () { },
        onNo: function () { },
        onShowed: function () { },
        onClosed: function () { }
    };

    $.messagebox.icon = {
        error: 'error'
        , info: 'info'
        , question: 'question'
        , warning: 'warning'
    };

    $.messagebox.button = {
        ok: 'ok'
        , yes: 'yes'
        , no: 'no'
    };

    $.messagebox.buttonType = {
        ok: { ok: $.messagebox.button.ok }
        , yesno: { yes: $.messagebox.button.yes, no: $.messagebox.button.no }
    };

    $(document).ready(function () {
        GenerateMessageboxStructure();
    });

    var messageboxIcon;
    var messageboxIconObjects = {};
    var messageboxButton;
    var messageboxButtonObjects = {};

    var body = document.getElementsByTagName('body');

    var STYLE_HIDE_ELEMENT = 'messagebox-hide-element';
    var ELEMENT_CLEAR_BOTH = '<div style="clear: both;" />';
    var FILE_TYPE_GIF = '.gif';
    var TYPE_FUNCTION = 'function';

    var messageboxIconPatternID = 'messagebox-icon-';
    var messageboxButtonPatternID = 'messagebox-button-';
    var messageboxIconPatternImage = '../images/messagebox_';

    var messageboxPanel;
    var messageboxPanelID = 'messagebox-panel';
    var messageboxPanelElement = '<div id="' + messageboxPanelID + '" />';

    var messageboxBody;
    var messageboxBodyID = 'messagebox-body';
    var messageboxBodyElement = '<div id="' + messageboxBodyID + '" />';

    var messageboxCode;
    var messageboxCodeID = 'messagebox-code';
    var messageboxCodeElement = '<div id="' + messageboxCodeID + '" />';

    var messageboxMessage;
    var messageboxMessageID = 'messagebox-message';
    var messageboxMessageElement = '<div id="' + messageboxMessageID + '" />';

    var messageboxButtons;
    var messageboxButtonsID = 'messagebox-buttons';
    var messageboxButtonsElement = '<div id="' + messageboxButtonsID + '" />';

    var modalBackground;
    var modalBackgroundID = 'messagebox-modalbackground';
    var modalBackgroundElement = '<div id="' + modalBackgroundID + '" />';

    var KEY_CODE_TAB = '9';
    var KEY_CODE_ENTER = '13';

    var documentTabProcessHandler = function (event) {
        if (event.keyCode == KEY_CODE_TAB) {
            SetDefaultButton(0);
        }

        event.preventDefault();
    };

    var buttonTabProcessHandler = function (event) {
        if (event.keyCode != KEY_CODE_TAB && event.keyCode != KEY_CODE_ENTER) {
            event.preventDefault();
        }

        event.stopPropagation();
    };

    var lastButtonTabProcessHandler = function (event) {
        if (event.keyCode != KEY_CODE_TAB && event.keyCode != KEY_CODE_ENTER) {
            event.preventDefault();
        }
        else if (event.keyCode == KEY_CODE_TAB) {
            SetDefaultButton(0);
            event.preventDefault();
        }

        event.stopPropagation();
    };

    function ShowMessagebox(options, buttonType) {
        SetMessageCode(options.code);

        SetMessageIcon(options.icon);

        SetMessage(options.message);

        SetMessageButtons(options, buttonType);

        SetMessageboxPosition();

        SetMessageboxShowStyle();

        SetDefaultButton(options.defaultButton);

        SetTabProcess();

        // Trig Event onShowed
        TrigOnShowed(options);
    }

    function HideMessagebox(options) {
        ResetTabProcess();

        SetMessageboxHideStyle();

        ResetMessageIcon();

        ResetMessageButtons();

        // Trig Event onClosed
        TrigOnClosed(options);
    }

    function GenerateMessageboxStructure() {
        $(body).append(messageboxPanelElement);
        $(body).append(modalBackgroundElement);

        messageboxPanel = document.getElementById(messageboxPanelID);
        modalBackground = document.getElementById(modalBackgroundID);

        GenerateMessageBody();

        GenerateMessageCode();

        GenerateMessageIcon();
        ResetMessageIcon();

        GenerateMessage();

        GenerateMessageButtons();
        ResetMessageButtons();

        SetMessageboxHideStyle();
    }

    function GenerateMessageBody() {
        $(messageboxPanel).append(messageboxBodyElement);
        messageboxBody = document.getElementById(messageboxBodyID);
    }

    function GenerateMessageCode() {
        $(messageboxBody).append(messageboxCodeElement);
        messageboxCode = document.getElementById(messageboxCodeID);
    }
    function SetMessageCode(msgCode) {
        if (msgCode != null && msgCode != '')
            $(messageboxCode).text('[' + msgCode + ']');
    }

    function GenerateMessageIcon() {
        var iconID, iconImage;

        for (var key in $.messagebox.icon) {
            iconID = messageboxIconPatternID + key;
            iconImage = messageboxIconPatternImage + key + FILE_TYPE_GIF;

            $(messageboxBody).append('<span id="' + iconID + '"><img src="' + iconImage + '" /></span>');

            messageboxIcon = document.getElementById(iconID);
            messageboxIconObjects[key] = messageboxIcon;
        }
    }
    function SetMessageIcon(icon) {
        switch (icon.toLowerCase()) {
            case $.messagebox.icon.error:
            case $.messagebox.icon.info:
            case $.messagebox.icon.question:
            case $.messagebox.icon.warning:

                messageboxIcon = messageboxIconObjects[icon.toLowerCase()];
                if ($(messageboxIcon).hasClass(STYLE_HIDE_ELEMENT)) {
                    $(messageboxIcon).removeClass(STYLE_HIDE_ELEMENT);
                }

                break;
        }
    }
    function ResetMessageIcon() {
        for (var key in messageboxIconObjects) {
            messageboxIcon = messageboxIconObjects[key];

            if (!$(messageboxIcon).hasClass(STYLE_HIDE_ELEMENT)) {
                $(messageboxIcon).addClass(STYLE_HIDE_ELEMENT);
            }
        }
    }

    function GenerateMessage() {
        $(messageboxBody).append(messageboxMessageElement);
        messageboxMessage = document.getElementById(messageboxMessageID);

        // Clear float
        $(messageboxBody).append(ELEMENT_CLEAR_BOTH);
    }
    function SetMessage(msg) {
        if (msg != null)
            $(messageboxMessage).html(msg);
    }

    function GenerateMessageButtons() {
        var buttonID;

        $(messageboxBody).append(messageboxButtonsElement);
        messageboxButtons = document.getElementById(messageboxButtonsID);

        for (var key in $.messagebox.button) {
            buttonID = messageboxButtonPatternID + key;
            $(messageboxButtons).append('<input type="button" id="' + buttonID + '" class="messagebox-button" />');

            messageboxButton = document.getElementById(buttonID);
            messageboxButtonObjects[key] = messageboxButton;
        }
    }
    function SetMessageButtons(options, buttonType) {
        switch (buttonType) {
            case $.messagebox.buttonType.ok:
            case $.messagebox.buttonType.yesno:

                for (var key in buttonType) {
                    messageboxButton = messageboxButtonObjects[key];

                    if ($(messageboxButton).hasClass(STYLE_HIDE_ELEMENT)) {
                        $(messageboxButton).removeClass(STYLE_HIDE_ELEMENT);
                    }

                    switch (key) {
                        case ($.messagebox.button.ok):
                            $(messageboxButton).val(options.ok);

                            if (typeof options.onOK === TYPE_FUNCTION)
                                $(messageboxButton).click(options.onOK);
                                
                            break;
                        case ($.messagebox.button.yes):
                            $(messageboxButton).val(options.yes);

                            if (typeof options.onYes === TYPE_FUNCTION)
                                $(messageboxButton).click(options.onYes);

                            break;
                        case ($.messagebox.button.no):
                            $(messageboxButton).val(options.no);

                            if (typeof options.onNo === TYPE_FUNCTION)
                                $(messageboxButton).click(options.onNo);

                            break;
                    }

                    $(messageboxButton).click(function () {
                        HideMessagebox(options);
                    });
                }

                break;
        }
    }
    function ResetMessageButtons() {
        for (var key in messageboxButtonObjects) {
            messageboxButton = messageboxButtonObjects[key];

            if (!$(messageboxButton).hasClass(STYLE_HIDE_ELEMENT)) {
                $(messageboxButton).addClass(STYLE_HIDE_ELEMENT);
            }

            $(messageboxButton).unbind('click');
        }
    }

    function SetDefaultButton(defaultButton) {
        // Selector ทั้ง 2 แบบ สามารถดึงปุ่มที่แสดงอยู่ ได้ทั้งคู่
        $(messageboxButtons).children('input:visible').eq(defaultButton).focus();
        //$(messageboxButtons).children('input:not(.messagebox-hide-element)').eq(defaultButton).focus();
    }

    function SetMessageboxPosition() {
        $(messageboxPanel).css('margin-top', $(messageboxPanel).outerHeight() / 2 * -1);
    }

    function SetTabProcess() {
        $(document).bind('keydown', documentTabProcessHandler);

        $(messageboxButtons).children('input:visible:not(:last)').bind('keydown', buttonTabProcessHandler);
        $(messageboxButtons).children('input:visible:last').bind('keydown', lastButtonTabProcessHandler);
    }
    function ResetTabProcess() {
        $(document).unbind('keydown', documentTabProcessHandler);

        $(messageboxButtons).children('input:visible').unbind('keydown');
    }

    function TrigOnShowed(options) {
        if (typeof options.onShowed === TYPE_FUNCTION) {
            options.onShowed();
        }
    }

    function TrigOnClosed(options) {
        if (typeof options.onClosed === TYPE_FUNCTION) {
            options.onClosed();
        }
    }

    function SetMessageboxShowStyle() {
        if ($(messageboxPanel).hasClass(STYLE_HIDE_ELEMENT)) {
            $(messageboxPanel).removeClass(STYLE_HIDE_ELEMENT);
            $(modalBackground).removeClass(STYLE_HIDE_ELEMENT);
        }
    }

    function SetMessageboxHideStyle() {
        if (!$(messageboxPanel).hasClass(STYLE_HIDE_ELEMENT)) {
            $(messageboxPanel).addClass(STYLE_HIDE_ELEMENT);
            $(modalBackground).addClass(STYLE_HIDE_ELEMENT);
        }
    }
})(jQuery);