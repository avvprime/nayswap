db.ref.child("contacts").orderByChild("ID").equalTo("U1EL5623").once("value",snapshot => {
	if (snapshot.exists()){
		const userData = snapshot.val();
		console.log("exists!", userData);
	}});





					





						else
						{
							var delData = db.ref('messages/'+room+"/isDeletedBefore");
							delData.set({
								delData:"true"
							});
							
							db.ref('rooms/user_'+uid+"/"+room).remove();
						}



