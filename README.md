![enter image description here](https://www.clicdata.com/wp-content/uploads/2021/01/microsoft-azure-blob-storage-logo.com_.png)
![enter image description here](https://miro.medium.com/max/800/1*Jj3L5aY6_7c0R9a8U0d_Qw.png)
# Send a file in a blob in an Azure storage account with a PHP API without SDK and libraries
> **Azure SDK :** ⛔ (*Not needed)*
> **PHP Composer :** ⛔ *(Not needed)*
> **Every type of file :** ✔️
> **HTML/CSS Interface :** ✔️
> 
![enter image description here](https://image.prntscr.com/image/flwtwY0rQeeqNNbpBZ_w1Q.png)


## You need to have :

A subcription Azure
An Azure storage account
A server with php installed or Azure Web Apps

## Create a container on Azure

1. Log in to the Azure portal and go to your storage account
2. Click on the "Containers" tab
3. Give a name to it and select your "Public access level" according to your needs and click Create
4. Go back to the first page your storage account
5. Click on the "Access Key" tab  and click "Show Keys", keep your key1 in a notebook (we don't need the connection string)
![enter image description here](https://image.prntscr.com/image/P4vx6URLT1KZrSI2bnJJkQ.png)

## Change variables with your environment

Open the file upload.php and change all variable with your values :
1. Line 21 : Change the variable "$containerName" with name of your container
2. Line 22 : Change the variable "$storageAccount" with name of your Storage Account
3. Line 77 : Change with your Key Access
-> Line 25 is a temporary folder in your web server, you can change it, and you need to check the permission folder 
-> Line 40 : You can limit the file size
-> Line 63 : When you upload the file name will be change with a unique and random name, you can remove it and use the original name

## File Types

On Line 46,51,54 you check the type of the file, you need to change according to your needs.
Line 52 and 54 you need to change the MIME type according to your file https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types 

## Final
You can now upload a file and personalize the PHP file according to your needs (use it with a database) :)
Now you can go in the Azure Portal -> Storage account -> Containers -> Click on your container and you will see all file you uploaded with the script

![enter image description here](https://image.prntscr.com/image/ulLKFwORQXGriuBmzi3Mjg.png)

If you need help you can contact me here : contact@liroyfr
