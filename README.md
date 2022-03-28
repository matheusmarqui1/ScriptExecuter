# Let's use your PHP script executer!

You were trying to find a simple shell php script executer? Now you have it .

# Using the ScriptExecuter
Open the ScriptExecuter by navigating to the root folder.

## Create a script

You can create a new script using the command
 **bin/run create --script='script_name' --class-name='ScriptClassName'** 
  After executing, your script folder and class are already created, you can modify it's methods by navigating to **src/ScriptExecuter/Scripts/ScriptClassName/ScriptClassName**. 

## Execute a script

You can easily run a script by running the command **bin/run execute --script='script_name'** 
or **bin/run execute** and choosing the script in the select using the arrow keys and hitting enter or typing the corresponding index and hitting enter.

## TODO

If you wanna contribute with this, you can implement these functionalities:

## Delete a script

Implement a delele command to completely delete a script including it's folder, contents and classes inside and the script insert in scripts.json 


