(function ($) {
    $.loadingpanel = {
        show: function (options) {
            var _options = $.extend({}, $.loadingpanel.defaults, options);
            ShowLoadingPanel(_options);
        }
        , hide: function () {
            HideLoadingPanel();
        }
    };

    $.loadingpanel.defaults = {
        message: ''
    };

    $(document).ready(function () {
        GenerateLoadingPanelStructure();
    });

    var body = document.getElementsByTagName('body');

    var STYLE_HIDE_PANEL = 'loadingpanel-hide-panel';
    var STYLE_SHOW_PANEL = 'loadingpanel-show-panel';

    var modalBackground;
    var modalBackgroundID = 'loadingpanel-modalbackground';
    var modalBackgroundElement = '<div id="' + modalBackgroundID + '" />';

    var loadingImageID = 'loadingpanel-image';
    var loadingImageElement = '<img id="' + loadingImageID + '" src="../image/loading.gif" />'

    var loadingMessage;
    var loadingMessageID = 'loadingpanel-message';
    var loadingMessageElement = '<span id="' + loadingMessageID + '" />';

    var loadingPanel;
    var loadingPanelID = 'loadingpanel-panel';
    var loadingPanelElement = '<div id="' + loadingPanelID + '">' + loadingImageElement + loadingMessageElement + '</div>';

    var documentPreventKeydownHandler = function (event) {
        event.preventDefault();
    };

    function ShowLoadingPanel(options) {
        SetMessage(options.message);

        SetLoadingPanelPosition();

        SetLoadingPanelShowStyle();

        SetDocumentPreventKeydown();
    }

    function HideLoadingPanel() {
        SetLoadingPanelHideStyle();

        ResetDocumentPreventKeydown();
    }

    function GenerateLoadingPanelStructure() {
        $(body).append(loadingPanelElement);
        $(body).append(modalBackgroundElement);

        loadingPanel = document.getElementById(loadingPanelID);
        modalBackground = document.getElementById(modalBackgroundID);
        loadingMessage = document.getElementById(loadingMessageID);

        SetLoadingPanelHideStyle();
    }

    function SetMessage(message) {
        if (message != null) {
            $(loadingMessage).text(message);
        }
    }

    function SetLoadingPanelPosition() {
        $(loadingPanel).css({
            'margin-top': $(loadingPanel).outerHeight() / 2 * -1
            , 'margin-left': $(loadingPanel).outerWidth() / 2 * -1
        });
    }

    function SetDocumentPreventKeydown() {
        $(document).bind('keydown', documentPreventKeydownHandler);
    }
    function ResetDocumentPreventKeydown() {
        $(document).unbind('keydown', documentPreventKeydownHandler);
    }

    function SetLoadingPanelShowStyle() {
        if ($(loadingPanel).hasClass(STYLE_HIDE_PANEL)) {
            $(loadingPanel).removeClass(STYLE_HIDE_PANEL)
            $(modalBackground).removeClass(STYLE_HIDE_PANEL)
        }
    }

    function SetLoadingPanelHideStyle() {
        if (!$(loadingPanel).hasClass(STYLE_HIDE_PANEL)) {
            $(loadingPanel).addClass(STYLE_HIDE_PANEL)
            $(modalBackground).addClass(STYLE_HIDE_PANEL)
        }
    }
} (jQuery));