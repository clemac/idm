function hide_obj(obj) {
	var element;
	if (element = findObj(obj)){
		jQuery(element).css("visibility","hidden");
	}
}


function admin_tech_selection_titre(titre, id, idom, nid)
{
	nom = titre.replace(/\W+/g, '_');
	findObj_forcer("znom_sauvegarde").value=nom;
	findObj_forcer("nom_sauvegarde").value=nom;
	aff_selection_titre(titre, id, idom, nid);
}


// Si Ajax est disponible, cette fonction l'utilise pour envoyer la requete.
// Si le premier argument n'est pas une url, ce doit etre un formulaire.
// Le deuxieme argument doit etre l'ID d'un noeud qu'on animera pendant Ajax.
// Le troisieme, optionnel, est la fonction traitant la reponse.
// La fonction par defaut affecte le noeud ci-dessus avec la reponse Ajax.
// En cas de formulaire, AjaxSqueeze retourne False pour empecher son envoi
// Le cas True ne devrait pas se produire car le cookie spip_accepte_ajax
// a du anticiper la situation.
// Toutefois il y toujours un coup de retard dans la pose d'un cookie:
// eviter de se loger avec redirection vers un telle page
// cf grenier
function AjaxSqueeze(trig, id, callback, event)
{
	var target = jQuery('#'+id);

	// position du demandeur dans le DOM (le donner direct serait mieux)
	if (!target.size()) {return true;}

	return !AjaxSqueezeNode(trig, target, callback, event);
}


// Les Submit avec attribut name ne sont pas transmis par JQuery
// Cette fonction clone le bouton de soumission en hidden
// Voir l'utilisation dans ajax_action_post dans inc/actions
// cf grenier
function AjaxNamedSubmit(input) {
	jQuery('<input type="hidden" />')
	.attr('name', input.name)
	.attr('value', input.value)
	.insertAfter(input);
	return true;
}

/*
function charger_id_url_si_vide (myUrl, myField, jjscript, event) {
	var Field = findObj_forcer(myField); // selects the given element
	if (!Field) return;

	if (Field.innerHTML == "") {
		charger_id_url(myUrl, myField, jjscript, event)
	}
	else {
		Field.style.visibility = "visible";
		Field.style.display = "block";
	}
}
*/
