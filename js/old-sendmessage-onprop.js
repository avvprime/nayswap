var message = escapeHtml(text);
					var room_name = first_person+"_"+second_person;

					db.ref("chat/messages/"+room_name).push({
						messageTimestamp:timeNow,
						messageSender:message_from,
						messageContent:message,
						messageRoom:room_name
					},
						function(error){
							if (error)
							{
								console.log("An error has occurated during creating room.")
							}
							else
							{

								db.ref("chat/user_rooms/"+message_from+"/"+room_name).set({
									userId:message_from,
									contactName:message_to_name,
									lastMessage:message,
									lastMessageTimestamp:timeNow
								},
								function(firstUserUpdateError){
									if (firstUserUpdateError)
									{
										console.log("An error has occurated during updating first user");
									}
									else{
										db.ref("chat/user_rooms/"+message_to+"/"+room_name).set({
											userId:message_to,
											contactName:message_from_name,
											lastMessage:message,
											lastMessageTimestamp:timeNow
										},
										function(secondUserUpdateError){
											if (secondUserUpdateError) {
												console.log("An error has occurated during updating second user");
											}
											else
											{
												Swal.fire({
													title:'Mesajınız iletildi',
													icon:'success',
													confirmButtonText:'Tamam'
												});
											}
										});
									}
								});
							}
						}
					);