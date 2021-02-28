window.addEventListener('DOMContentLoaded',function(){
	// Recherche des clients en fonction de leurs addresses mails et date de venue
    let formulaire = document.querySelector('.formSearch');
    let lesClientsRecherches = document.querySelector('#lesClientsRecherches');

    formulaire.addEventListener('keyup',function(event){
    	let email = document.querySelector('.formSearch [name="email"]').value;
    	let arrival_date = document.querySelector('.formSearch [name="arrival_date"]').value;

		const url = `./backoffice/clients/search/` + email + `/` + arrival_date;

		fetch(url)
			// traiter la réponse comme du JSON
	        .then(response => response.json())
	        // réceptionner les datas
	        .then(datas => {
	          // reconstruire la liste des villes
	          lesClientsRecherches.innerHTML = datas.clients.map(client => {
	      		 			return `<li>${client.firstname} ${client.lastname} - ${client.email} - ${client.arrival_date}</li>`;
	      		 		}).join('');
	        });
    })   
})