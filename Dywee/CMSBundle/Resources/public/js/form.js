$(document).ready(function() {

    var choices = [
        {
            key: 'text',
            value: 'Zone de texte'
        },
        {
            key: 'form',
            value: 'Formulaire'
        }
    ];

    var currentIndex = null;

    //Créer un conflit entre l'éditeur de texte et le plugin sortable
    $("#dywee_cmsbundle_page_pageElements").sortable({
        placeholder: "dywee-pageElement-placeholder",
        forcePlaceholderSize: true
    });


    /*******************************************************/
    /***                                               *****/
    /***      1. GESTION DES MODAL ET CHILDARGUMENTS   *****/
    /***                                               *****/
    /*******************************************************/

    {
    //Affichage ou non des champs liés au menu
    if(!$("#dywee_cmsbundle_page_inMenu").attr("checked"))
        $("#menuSettings").attr('style', 'display: none');


    $("#dywee_cmsbundle_page_inMenu").on('change', function()
    {
        $("#menuSettings").slideToggle('slow');
    });

    //Remplissage automatique du menuName si vide
    if($("#dywee_cmsbundle_page_menuName").val() == '')
    {
        $("#dywee_cmsbundle_page_name").change(function()
        {
            $("#dywee_cmsbundle_page_menuName").val($("#dywee_cmsbundle_page_name").val());
        })
    }
    }


    //Gestion du childArgument des pages

    var modalLabel = '';
    var urlForAjax = '';
    var createButtonRedirection = '';

    $("#dywee_cmsbundle_page_type").change(function() {
        var value = $(this).val();

        ///*
        //Formulaire
        if(value == 8)
        {
            modalLabel = 'Choisissez un formulaire à afficher sur la page';
            urlForAjax = Routing.generate('dywee_customForm_json');
            createButtonRedirection = 'dywee_customForm_add';
            ajaxify(callBackForType);
        }

        //Musique
        else if(value == 12)
        {
            modalLabel = 'Choisissez une galerie à afficher sur la page';
            urlForAjax = Routing.generate('dywee_musicGallery_json');
            createButtonRedirection = 'dywee_musicGallery_add';
            ajaxify(callBackForType);
        }
        //*/
    })

    function ajaxify(callback) {
        $("#dyweeModalCloseButton").html('Fermer');
        $("#dyweeModalContinueButton").html('Valider').addClass('disabled');
        $("#dyweeModalLabel").html(modalLabel);
        $("#dyweeModal .modal-body").html('<i class="fa fa-spinner fa-spin"></i> Chargement des données');
        $("#dyweeModal").modal('show');

        $.ajax({
            url: urlForAjax,
            dataType: 'json',
            type: 'post',
            success: callback
        });
    }

    function ProcessAjaxForPageElement(type, prototype)
    {
        $.ajax({
            url: Routing.generate('dywee_cms_getPageElementDashboard_byAjax'),
            dataType: 'json',
            type: 'post',
            data: {objectName: type},
            success: function(data)
            {
                if(type == 'form')
                    showDashboardForForm(data, prototype);
            }
        });
    }

    //OLD Callback for PAGE TYPE
    function callBackForType(data) {
        handleData(data, setChildArgument);
    }

    //New Callback for pageElement
    function callBackForPageElement(data) {
        handleDataForPageElement(data);
    }

    function showDashboardForForm(data, $prototype)
    {
        $prototype.find('.box-header').html('<h2 class="box-title">Formulaire</h2>');
        $inputToFill = $prototype.find('[id$="_content"]');

        var value = $inputToFill.val();

        $div = $prototype.find('.for-user');

        if($div.length == 0)
            $div = $('<div class="for-user">');

        $div.html('');

        $.each(data, function(index, item)
        {
            $radio = $('<input type="radio" name="test">');
            $toAdd = $('<div class="radio">').append($('<label>').html(item.name).prepend($radio))

            $div.append($toAdd);

            if(item.id == value)
                $radio.attr('checked', 'checked');

            $radio.click(function(e) {
                $inputToFill.val(item.id);
            });
        });

        $prototype.find('.box-body').append($div);
    }

    function handleData(data, callback) {
        if(data.type == 'success')
        {
            $("#dyweeModal .modal-body").html(setTable(data.data, callback));
            $("#dyweeModalContinueButton").html('Valider').removeClass('disabled');
        }
        else if(data.type == 'empty')
        {
            var toReturn = $('<p>Vous devez d\'abord configurer des éléments</p>');
            var refreshButton = $("<button>").addClass('btn btn-default').html('<i class="fa fa-refresh"></i> Rafraichir').on('click', ajaxify);
            var createButton = $("<a>").addClass('btn btn-success').html('<i class="fa fa-plus"></i> Créer un élément').attr('href', Routing.generate(createButtonRedirection)).attr('target', '_blanck');
            toReturn.append($('<p>')).append(createButton, ' ', refreshButton);
            $("#dyweeModal .modal-body").html(toReturn);
        }

        else
            $("#dyweeModal .modal-body").html('<p>Une erreur est survenue</p>');
    }

    function handleDataForPageElement(data) {
        if(data.type == 'success')
            var html = setTable(data.data, setPageElementValue);
        else if(data.type == 'empty')
        {
            var $html = $('<p>Vous devez d\'abord configurer des éléments</p>');
            var refreshButton = $("<button>").addClass('btn btn-default').html('<i class="fa fa-refresh"></i> Rafraichir').on('click', ajaxify);
            var createButton = $("<a>").addClass('btn btn-success').html('<i class="fa fa-plus"></i> Créer un élément').attr('href', Routing.generate('dywee_customForm_add')).attr('target', '_blanck');
        }
        else
            var html = $('<p>').html('Une Erreur est survenue');

        //console.log($("input[id$='"+currentIndex+"_content']").parents('div.box-body').html());
        $("[id$='"+currentIndex+"_content']").parents('div.box-body').append(html).find('.loader').remove();
    }

    function setPageElementValue($link, id) {
        var field_id = $link.parents('div.box-body').find('[id$="_content"]').val(id);
    }

    function setTable(data, callback) {

        var $table = $('<table class="table table-bordered">');

        $(data).each(function(){
            $(this).each(function(){
                var $row = $('<tr>');
                var $cell  = $('<td>');
                var $nameLink = $('<a href="#">');
                var $selectBtn = $('<a href="#">Choisir</a>');

                $nameLink.html(this.name);

                $table.append(
                    $row.append(
                        $cell.clone().append(
                            $nameLink
                        )
                    )
                    .append(
                        $cell.clone().append(
                            $selectBtn
                        )
                    )
                );

                console.log($table);

                var id = this.id;

                $nameLink.click(function(e) {
                    callback($(this), id);
                    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                    return false;
                });

                $selectBtn.click(function(e) {
                    callback($(this), id);
                    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                    return false;
                });
            })
        })

        return $table;
    }

    function setRadioBoxes(data, callback) {

        var $form = $('<div class="radio">');

        $(data).each(function(){
            $(this).each(function(){
                var $input = $('<input type="radio" name="' + data.name + '" id="' + data.name + '_' + this.id + '" value="' + this.id + '">' + data.name);

                $form.append(
                    $('<label>').append(
                        $input
                    )
                );

                console.log($form);

                var id = this.id;

                $input.click(function(e) {
                    callback($(this), id);
                    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                    return false;
                });
            })
        })

        return $table;
    }


    /*******************************************************/
    /***                                               *****/
    /***      2. GESTION DES PAGEELEMENTS              *****/
    /***                                               *****/
    /*******************************************************/

    var $container = $('div#page_elements_container');

    // On ajoute un lien pour ajouter une nouvelle catégorie
    var $addLink = $('<a href="#" id="add_element" class="btn btn-default btn-lg btn-block"><i class="fa fa-plus"></i> Ajouter un élement</a>');
    $container.after($('<div class="col-xs-12"></div>').html($addLink));

    // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
    $addLink.click(function(e) {
        handleModal();
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.children('div').length;

    // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
    if (index == 0) {
        addElement($container, 'text');
    } else {
        // Pour chaque catégorie déjà existante, on traite les champs
        $('div#dywee_cmsbundle_page_pageElements .box').each(function() {

            var $field = $(this).find('[id$="_content"]');
            var type = $(this).find('[id$="_type"]').val();

            console.log(type);

            if(type == 'text')
            {
                console.log('type texte detecté');
                console.log('begin');

                $field.parent().removeClass('hide');
                CKEDITOR.disableAutoInline = true;
                CKEDITOR.inline( $field.attr('id') , trsteelConfig );

                console.log('end');

            }
            else if(type == 'form')
            {
                console.log('type form détecté');
                ProcessAjaxForPageElement('form', $(this));

            }
            addDeleteLink($(this));
            index++;
        });

    }

    //Correction de l'index qui prend en compte le bouton
    index--;

    // La fonction qui ajoute un formulaire Categorie
    function addElement($container, type) {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ
        var $prototype = $($container.find('div#dywee_cmsbundle_page_pageElements').attr('data-prototype').replace(/__name__label__/g, 'Catégorie n°' + (index+1))
            .replace(/__name__/g, index));

        $prototype.find('.box-title').html('type: '+type);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        processPrototype($prototype, type, index);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;
    }


    /*******************************************************/
    /***                                               *****/
    /***      3. MODAL POUR LES PAGEELEMENTS           *****/
    /***                                               *****/
    /*******************************************************/

    function handleModal() {
        var html = $('<p>');

        //Mise en forme des choix pour la modal
        $.each(choices, function(i, data)
        {
            var btn = $('<a href="#" class="btn btn-default">' + data.value + '</a>');

            html.append($('<p>').append(btn));

            btn.click(function(e) {
                addElement($container, data.key);
                $("#dyweeModal").modal('hide');
                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });

        });

        $("#dyweeModalCloseButton").hide();
        $("#dyweeModalContinueButton").hide();
        $("#dyweeModalLabel").html('Choisissez un élément à ajouter');
        $("#dyweeModal .modal-body").html(html);
        $("#dyweeModal").modal('show');
    }

    function processPrototype($prototype, type, index) {
        currentIndex = index;
        $prototype.find("input[id$='_type']").val(type);
        if(type == 'text')
            addTextArea($prototype, index);
        else if(type == 'form')
            addForm($prototype, index);

    }

    function addTextArea($prototype, index) {
        var $field = $prototype.find("[id$='_content']").val('<p>Tapez votre texte ici</p>');
        $field.parent().removeClass('hide');
        CKEDITOR.inline($field.attr('id'), trsteelConfig);
    }

    function addForm($prototype, index) {
        modalLabel = 'Choisissez un formulaire à afficher sur la page';
        $prototype.find('.box-body').append('<div class="for-user"><i class="fa fa-spinner fa-spin"></i> Chargement de vos formulaires</div>');
        urlForAjax = Routing.generate('dywee_customForm_json');
        createButtonRedirection = 'dywee_customForm_add';
        //ajaxify(callBackForPageElement);

        ProcessAjaxForPageElement('form', $prototype);
    }

});

function setChildArgument($link, data) {
    $("#dyweeModal").modal('hide');
    $("#dywee_cmsbundle_page_childArguments").val(data);
}

function processExistingElement($element) {

    addDeleteLink($element.parent());

    var type = $element.find('input[id$="_value"]').val();

    /*if(type == 'text')
        CKEDITOR.replace($element.find('input[id$="_content"]').val());*/
}

// La fonction qui ajoute un lien de suppression d'une catégorie
function addDeleteLink($prototype) {
    // Création du lien
    $deleteLink = $('<a href="#" class="btn btn-danger"><i class="fa fa-trash-o"></i> Supprimer</a>');

    // Ajout du lien
    $prototype.find(".box-footer").html($deleteLink);

    // Ajout du listener sur le clic du lien
    $deleteLink.click(function (e) {
        $prototype.remove();
        e.preventDefault(); // évite qu'un # apparaisse dans l'URL
        return false;
    });
}
