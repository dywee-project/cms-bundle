$(document).ready(function () {

    var currentIndex = null;

    //Créer un conflit entre l'éditeur de texte et le plugin sortable
    function setBoxSortable()
    {
        console.log('sortable');
        $("#dywee_cmsbundle_page_pageElements").sortable({
            placeholder: "dywee-pageElement-placeholder",
            forcePlaceholderSize: true,
            handle: ".box-header",
            update: function ( event, ui ) {
                $.each($('.box'), function (index, box) {
                    $(box).find('input[id$="_displayOrder"]').val(index + 1);
                })
            }
        });
    }

    /*******************************************************/
    /***                                               *****/
    /***      1. GESTION DES MODAL ET CHILDARGUMENTS   *****/
    /***                                               *****/
    /*******************************************************/

    {
    //Affichage ou non des champs liés au menu
        if (!$("#page_inMenu").attr("checked")) {
            $("#menuSettings").css('display', 'none');
        }


        $("#page_inMenu").on('change', function () {
            $("#menuSettings").slideToggle('slow');
        });

    //Remplissage automatique du menuName si vide
    if ($("#dywee_cmsbundle_page_menuName").val() == '') {
        $("#dywee_cmsbundle_page_name").change(function () {
            $("#dywee_cmsbundle_page_menuName").val($("#dywee_cmsbundle_page_name").val());
        })
    }
    }


    //Gestion du childArgument des pages

    var modalLabel = '';
    var urlForAjax = '';
    var createButtonRedirection = '';

    function ProcessAjaxForPageElement(type, $prototype)
    {
        setBoxSortable();
        $.ajax({
            url: Routing.generate('cms_getPageElementDashboard_byAjax'),
            dataType: 'json',
            type: 'post',
            data: {objectName: type},
            success: function (data) {
                switch (type) {
                    case 'form':
                        $prototype.find('.box-header .box-title').html('Formulaire');
                        showDashboard(data, $prototype);
                        break;
                    case 'musicGallery':
                        $prototype.find('.box-header .box-title').html('Galerie musicale');
                        showDashboard(data, $prototype);
                        break;
                    case 'carousel':
                        $prototype.find('.box-header .box-title').html('Carousel');
                        showDashboard(data, $prototype);
                        break;
                }
            }
        });
    }

    function showDashboard(data, $prototype)
    {
        $inputToFill = $prototype.find('[id$="_content"]');

        var value = $inputToFill.val();

        $div = $prototype.find('.for-user');

        if ($div.length == 0) {
            $div = $('<div class="for-user">');
        }

        $div.html('');

        if (data.length == 0) {
            $div.html('<p>Vous n\'avez pas encore créé d\'élements</p>');
        }

        var uniqId = Math.random().toString(36).slice(-5);

        $.each(data, function (index, item) {
            $radio = $('<input type="radio" name="' + uniqId + '">');
            $toAdd = $('<div class="radio">').append($('<label>').html(item.name).prepend($radio))

            $div.append($toAdd);

            if (item.id == value) {
                $radio.attr('checked', 'checked');
            }

            $radio.click(function (e) {
                $inputToFill.val(item.id);
            });
        });

        $prototype.find('.box-body').append($div);
    }


    function handleDataForPageElement(data)
    {
        if (data.type == 'success') {
            var html = setTable(data.data, setPageElementValue);
        } else if (data.type == 'empty') {
            var $html = $('<p>Vous devez d\'abord configurer des éléments</p>');
            var refreshButton = $("<button>").addClass('btn btn-default').html('<i class="fa fa-refresh"></i> Rafraichir').on('click', ajaxify);
            var createButton = $("<a>").addClass('btn btn-success').html('<i class="fa fa-plus"></i> Créer un élément').attr('href', Routing.generate('dywee_customForm_add')).attr('target', '_blanck');
        } else {
            var html = $('<p>').html('Une Erreur est survenue');
        }

        //console.log($("input[id$='"+currentIndex+"_content']").parents('div.box-body').html());
        $("[id$='" + currentIndex + "_content']").parents('div.box-body').append(html).find('.loader').remove();
    }

    function setPageElementValue($link, id)
    {
        var field_id = $link.parents('div.box-body').find('[id$="_content"]').val(id);
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
    $addLink.click(function (e) {
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
        $('div#page_pageElements .box').each(function () {

            var $field = $(this).find('[id$="_content"]');
            var type = $(this).find('[id$="_type"]').val();

            console.log('type ' + type + ' détecté');

            switch (type) {
                case 'text' :
                    $(this).find('.box-title').html(plugins[0].value);

                    $field.parent().removeClass('hide');
                    CKEDITOR.disableAutoInline = true;
                    //trsteelConfig.extraPlugins = 'sourcedialog';
                    //console.log(trsteelConfig);
                    //CKEDITOR.inline( $field.attr('id'), trsteelConfig );
                    CKEDITOR.inline($field.attr('id'));
                    break;

                default: ProcessAjaxForPageElement(type, $(this));
            }

            addDeleteLink($(this));
            index++;
        });
    }

    //Correction de l'index qui prend en compte le bouton
    index--;


    /*******************************************************/
    /***                                               *****/
    /***      3. MODAL POUR LES PAGEELEMENTS           *****/
    /***                                               *****/
    /*******************************************************/

    function handleModal()
    {
        var html = $('<p>');

        //Mise en forme des choix pour la modal
        $.each(plugins, function (i, data) {
            if (data.key) {
                var btn = $('<a href="#" class="btn btn-default"><i class="fa fa-' + data.icon + '"></i> ' + data.value + '</a>');

                if (data.active == false) {
                    btn.addClass('disabled').append(' (en préparation)');
                }

                html.append($('<p>').append(btn));

                btn.click(function (e) {
                    addElement($container, data.key);
                    $("#dyweeModal").modal('hide');
                    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                    return false;
                });
            }


        });

        $("#dyweeModalCloseBtn").hide();
        $("#dyweeModalContinueBtn").hide();
        $("#dyweeModalLabel").html('Choisissez un élément à ajouter');
        $("#dyweeModal .modal-body").html(html);
        $("#dyweeModal").modal('show');
    }

    // La fonction qui ajoute un formulaire Categorie
    function addElement($container, type)
    {
        // Dans le contenu de l'attribut « data-prototype », on remplace :
        // - le texte "__name__label__" qu'il contient par le label du champ
        // - le texte "__name__" qu'il contient par le numéro du champ

        var $prototype = $($container.find('div#page_pageElements').attr('data-prototype').replace(/__name__label__/g, 'Catégorie n°' + (index + 1))
            .replace(/__name__/g, index));

        $prototype.find('.box-title').html('type: ' + type);

        // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
        addDeleteLink($prototype);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        processPrototype($prototype, type, index);

        // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
        index++;

        setBoxSortable();
    }

    function processPrototype($prototype, type, index)
    {
        currentIndex = index;
        $prototype.find("input[id$='_type']").val(type);

        console.log('processPrototype | type: ' + type);
        switch (type) {
            case 'text':
                var $field = $prototype.find("[id$='_content']").val('<p>Tapez votre texte ici</p>');
                $field.parent().removeClass('hide');
                CKEDITOR.inline($field.attr('id'));
                break;

            case 'form':
                $prototype.find('.box-body').append('<div class="for-user"><i class="fa fa-spinner fa-spin"></i> Chargement de vos formulaires</div>');
                ProcessAjaxForPageElement('form', $prototype);
                break;

            case 'musicGallery':
                $prototype.find('.box-body').append('<div class="for-user"><i class="fa fa-spinner fa-spin"></i> Chargement de vos galeries musicales</div>');
                ProcessAjaxForPageElement('musicGallery', $prototype);
                break;

            case 'carousel':
                $prototype.find('.box-body').append('<div class="for-user"><i class="fa fa-spinner fa-spin"></i> Chargement de vos carousels d\'images</div>');
                ProcessAjaxForPageElement('carousel', $prototype);
                break;
        }
    }
});

// La fonction qui ajoute un lien de suppression d'une catégorie
function addDeleteLink($prototype)
{
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
