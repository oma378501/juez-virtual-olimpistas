#include <cstdlib>
#include <sstream>
#include <string.h>
#include <stdio.h>
#include "vjgrader.cpp"
#include "solutionattempt.h"

#define INPUT_DATA 6

using namespace std;

int main(int argc, char **argv)
{
    if (argc != INPUT_DATA)
    {
        printf("There is no input data. The count data is : %d\n", argc);
        return 1;
    }

    SolutionAttempt attempt;
    attempt.id = atoi(argv[1]);
    attempt.appToCompile = argv[2];
    attempt.testInputs = argv[3];
    attempt.expectedOutputs = argv[4];
    
    if (strcmp(argv[5], "CPP") == 0)
    {
        attempt.language = CPP;
    }
    else
    {
        attempt.language = ANSI_C;
    }

    attempt.status = COMPILATION_ERROR;
    std::ostringstream appNaming;
    appNaming << "execution/compilation" << attempt.id;
    string appName = appNaming.str();

    std::ostringstream outputNaming;
    outputNaming << "execution/output" << attempt.id;
    string output = outputNaming.str();

    attempt.compiledApp = appName.c_str();
    attempt.generatedOutputs = output.c_str();
    grade(attempt);

    printf("ID: %d\n", attempt.id);
    if (attempt.status == OK)
    {
        printf("Grade: %d\n", attempt.grade);
    }
    else
    {
       printf("Error: \n");
       printf("%s\n", attempt.errorMessage);
    }
    
    return 0;
}
