<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:noNamespaceSchemaLocation="PDFschema.xsd">

	<xsl:output method="xml" encoding="UTF-8" indent="no" />

	<xsl:template match="Data">
		<!-- TODO: Auto-generated template -->
		<Data xmlns:od="urn:schemas-microsoft-com:officedata" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:noNamespaceSchemaLocation="PDFschema.xsd">
			<xsl:for-each select="fields">
				<xsl:sort select="nummer"></xsl:sort>
				<xsl:comment>
					<xsl:text>Kategorie: </xsl:text>
					<xsl:value-of select="Optionsfeld3"></xsl:value-of>
				</xsl:comment>
				<xsl:element name="textabschnitt1">
					<xsl:element name="anbieter">
						<xsl:value-of select="anbieter" />
						<xsl:text>&#xD;</xsl:text>
					</xsl:element>
					<xsl:element name="internet">
						<xsl:value-of select="internet" />
					</xsl:element>
				</xsl:element>
				<xsl:element name="programm">
					<xsl:value-of select="programm" />
				</xsl:element>
				<xsl:element name="beschreibung">
					<xsl:if test="Inhalt !=''">
						<xsl:element name="beschreibungUeberschrift">
							<xsl:text>Inhalt:&#xD;</xsl:text>
						</xsl:element>
						<xsl:element name="beschreibungInhalt">
							<xsl:value-of select="Inhalt"></xsl:value-of>
						</xsl:element>
						<xsl:text>&#xD;</xsl:text>
					</xsl:if>
					<xsl:if test="Lernziel != ''">
						<xsl:element name="beschreibungsUeberschrift">
							<xsl:text>Lernziel:&#xD;</xsl:text>
						</xsl:element>
						<xsl:element name="beschreibungLernziel">
							<xsl:value-of select="Lernziel"></xsl:value-of>
						</xsl:element>
					</xsl:if>
				</xsl:element>
				<xsl:element name="textabschnitt2">
					<xsl:if test="besonderheit !=''">
						<xsl:element name="hinweise">
							<xsl:text>Besonderheit:&#xD;</xsl:text>
						</xsl:element>
						<xsl:element name="besonderheit">
							<xsl:value-of select="besonderheit"></xsl:value-of>
						</xsl:element>
					</xsl:if>
					<xsl:if test="termine !=''">
						<xsl:element name="hinweise">
							<xsl:text>Termine:&#xD;</xsl:text>
						</xsl:element>
						<xsl:element name="termine">
							<xsl:value-of select="termine"></xsl:value-of>
						</xsl:element>
					</xsl:if>
				</xsl:element>
				
				<xsl:element name="nummer">
					<xsl:value-of select="nummer"></xsl:value-of>
				</xsl:element>
			</xsl:for-each>
			

			<!-- <xsl:element name="berufsschule">
				<xsl:value-of select="berufsschule" />
			</xsl:element>
			<xsl:element name="eins">
				<xsl:value-of select="eins" />
			</xsl:element>
			<xsl:element name="elf">
				<xsl:value-of select="elf" />
			</xsl:element>
			<xsl:element name="foerderschule">
				<xsl:value-of select="foerderschule" />
			</xsl:element>
			<xsl:element name="fuen">
				<xsl:value-of select="fuenf" />
			</xsl:element>
			<xsl:element name="kika">
				<xsl:value-of select="kika" />
			</xsl:element>
			<xsl:element name="lehrer">
				<xsl:value-of select="lehrer" />
			</xsl:element> -->
			
		</Data>
	</xsl:template>
</xsl:stylesheet>